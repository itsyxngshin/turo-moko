<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use App\Models\QuizResult;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AssessmentBuilderController extends Controller
{
    /**
     * Save base64 image to storage and return path
     */
    private function saveBase64Image($base64String, $imageName)
    {
        if (!$base64String || !str_starts_with($base64String, 'data:image/')) {
            return null;
        }

        try {
            // Extract image data
            $image = str_replace('data:image/', '', $base64String);
            $image = explode(';base64,', $image);
            $extension = $image[0];
            $imageData = base64_decode($image[1]);

            // Generate unique filename
            $filename = 'question_images/' . time() . '_' . uniqid() . '.' . $extension;

            // Save to storage
            Storage::disk('public')->put($filename, $imageData);

            return $filename;
        } catch (\Exception $e) {
            \Log::error('Failed to save image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Show the assessment builder form
     */
    public function create(Request $request)
    {
        $courses = Course::all();
        $quiz = null;
        $quizItems = [];
        
        // If quiz_id is provided, load the quiz for editing
        if ($request->has('quiz_id')) {
            $quiz = Quiz::with(['questions.choices'])->find($request->quiz_id);
            
            // Transform questions into items format for the builder
            if ($quiz) {
                $quizItems = $quiz->questions->map(function($q) {
                    $item = [
                        'id' => $q->id,
                        'type' => $q->type,
                        'text' => $q->question_text,
                        'questionText' => $q->question_text, // Alpine.js uses questionText
                        'points' => $q->points,
                        'modelAnswer' => $q->model_answer ?? '',
                        'imageData' => $q->image_path ? asset('storage/' . $q->image_path) : null,
                        'imageName' => $q->image_name,
                    ];
                    
                    if ($q->type === 'multiple_choice') {
                        $item['options'] = $q->choices->pluck('choice_text')->toArray();
                        $correctIndex = $q->choices->search(function($c) { return $c->is_correct; });
                        $item['correctAnswer'] = $correctIndex !== false ? $correctIndex : 0;
                    } elseif ($q->type === 'true_false') {
                        $item['trueText'] = 'True';
                        $item['falseText'] = 'False';
                        $correctChoice = $q->choices->firstWhere('is_correct', true);
                        $item['correctAnswer'] = $correctChoice && $correctChoice->choice_text === 'True' ? 'true' : 'false';
                    } elseif ($q->type === 'short_answer') {
                        $item['shortAnswerField'] = ''; // Initialize for Alpine
                        // Convert model_answer to acceptedAnswers array for backward compatibility
                        if (!empty($q->model_answer)) {
                            // Check if model_answer is JSON array or single string
                            $decoded = json_decode($q->model_answer, true);
                            $item['acceptedAnswers'] = is_array($decoded) ? $decoded : [$q->model_answer];
                        } else {
                            $item['acceptedAnswers'] = [''];
                        }
                    } elseif ($q->type === 'long_answer') {
                        $item['longAnswerField'] = ''; // Initialize for Alpine
                    }
                    
                    return $item;
                })->toArray();
            }
        }
        
        return view('implementor.assessment-builder', compact('courses', 'quiz', 'quizItems'));
    }

    /**
     * Store a new assessment
     */
    public function store(Request $request)
    {
        // Debug: Log the request
        \Log::info('Assessment Builder Store Request', [
            'all_data' => $request->all(),
            'questions' => $request->input('questions'),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        // Decode questions JSON for validation
        $questionsData = json_decode($request->questions, true);
        if (!is_array($questionsData)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid questions data format.',
                    'errors' => ['questions' => ['Questions data is invalid']]
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['questions' => 'Questions data is invalid'])
                ->withInput()
                ->with('error', 'Invalid questions data format.');
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:quiz,exam',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'closing_schedule' => 'nullable|date',
            'timer_hours' => 'nullable|integer|min:0|max:23',
            'timer_minutes' => 'nullable|integer|min:0|max:59',
            'submission_limit' => 'nullable|integer|min:1',
            'questions' => 'required|string', // Changed to string since we're sending JSON
        ]);

        
        // Add custom validation for questions array
        $validator->after(function ($validator) use ($questionsData) {
            if (empty($questionsData)) {
                $validator->errors()->add('questions', 'At least one question is required.');
                return;
            }

            foreach ($questionsData as $index => $question) {
                if (empty($question['text'])) {
                    $validator->errors()->add("questions.{$index}.text", 'Question text is required.');
                }
                
                if (!in_array($question['type'], ['multiple_choice', 'true_false', 'short_answer', 'long_answer'])) {
                    $validator->errors()->add("questions.{$index}.type", 'Invalid question type.');
                }
                
                if (!isset($question['points']) || $question['points'] < 1) {
                    $validator->errors()->add("questions.{$index}.points", 'Points must be at least 1.');
                }
                
                if ($question['type'] === 'multiple_choice') {
                    if (empty($question['options']) || count($question['options']) < 2) {
                        $validator->errors()->add("questions.{$index}.options", 'Multiple choice questions must have at least 2 options.');
                    }
                    
                    $validOptions = array_filter($question['options'], function($opt) {
                        return !empty(trim($opt));
                    });
                    
                    if (count($validOptions) < 2) {
                        $validator->errors()->add("questions.{$index}.options", 'Multiple choice questions must have at least 2 non-empty options.');
                    }
                }

                // Short answer questions must have acceptedAnswers or modelAnswer defined
                if ($question['type'] === 'short_answer') {
                    // Support both old (modelAnswer) and new (acceptedAnswers) formats
                    $hasAnswers = false;
                    
                    if (isset($question['acceptedAnswers']) && is_array($question['acceptedAnswers'])) {
                        $validAnswers = array_filter($question['acceptedAnswers'], function($ans) {
                            return !empty(trim($ans));
                        });
                        $hasAnswers = count($validAnswers) > 0;
                    } elseif (isset($question['modelAnswer']) && !empty(trim($question['modelAnswer']))) {
                        $hasAnswers = true;
                    }
                    
                    if (!$hasAnswers) {
                        $validator->errors()->add("questions.{$index}.acceptedAnswers", 'At least one answer is required for short answer questions.');
                    }
                }
            }
        });

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        try {
            DB::beginTransaction();

            // Determine status based on action
            $status = $request->input('action') === 'publish' ? 'Published' : 'Draft';

            // Get the next order number across ALL timeline items
            $maxOrder = max(
                \App\Models\Module::where('course_id', $request->course_id)->max('order') ?? 0,
                \App\Models\Assignment::where('course_id', $request->course_id)->max('order') ?? 0,
                Quiz::where('course_id', $request->course_id)->max('order') ?? 0,
                \App\Models\ProgramEvaluation::where('course_id', $request->course_id)->max('order') ?? 0,
                \App\Models\Announcement::where('course_id', $request->course_id)->max('order') ?? 0,
                \App\Models\SectionHeader::where('course_id', $request->course_id)->max('order') ?? 0
            );

            // Create the quiz
            $quiz = Quiz::create([
                'course_id' => $request->course_id,
                'quiz_title' => $request->title,
                'description' => $request->description,
                'status' => $status,
                'start_date' => now(),
                'end_date' => $request->closing_schedule ?: now()->addDays(7),
                'timer_hours' => $request->timer_hours ?: 0,
                'timer_minutes' => $request->timer_minutes ?: 0,
                'submission_limit' => $request->submission_limit ?: 1,
                'visibility' => true,
                'order' => $maxOrder + 1,
            ]);

            // Process questions (already decoded above)
            $questions = $questionsData;
            
            foreach ($questions as $questionData) {
                // Prepare model_answer field
                $modelAnswer = null;
                if ($questionData['type'] === 'short_answer') {
                    // For short answer, store acceptedAnswers as JSON, or fallback to modelAnswer
                    if (isset($questionData['acceptedAnswers']) && is_array($questionData['acceptedAnswers'])) {
                        $validAnswers = array_filter($questionData['acceptedAnswers'], function($ans) {
                            return !empty(trim($ans));
                        });
                        $modelAnswer = json_encode(array_values($validAnswers));
                    } elseif (isset($questionData['modelAnswer'])) {
                        $modelAnswer = $questionData['modelAnswer'];
                    }
                } else {
                    $modelAnswer = $questionData['modelAnswer'] ?? null;
                }
                
                // Create the question
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['text'],
                    'model_answer' => $modelAnswer,
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                ]);

                // Handle different question types
                switch ($questionData['type']) {
                    case 'multiple_choice':
                        // Create choices for multiple choice questions
                        if (isset($questionData['options']) && is_array($questionData['options'])) {
                            foreach ($questionData['options'] as $index => $optionText) {
                                if (!empty(trim($optionText))) {
                                    Choice::create([
                                        'question_id' => $question->id,
                                        'choice_text' => trim($optionText),
                                        'is_correct' => (string)$questionData['correctAnswer'] === (string)$index,
                                    ]);
                                }
                            }
                        }
                        break;

                    case 'true_false':
                        // Create choices for true/false questions
                        if (isset($questionData['trueText']) && !empty(trim($questionData['trueText']))) {
                            Choice::create([
                                'question_id' => $question->id,
                                'choice_text' => trim($questionData['trueText']),
                                'is_correct' => (string)$questionData['correctAnswer'] === 'true',
                            ]);
                        }
                        if (isset($questionData['falseText']) && !empty(trim($questionData['falseText']))) {
                            Choice::create([
                                'question_id' => $question->id,
                                'choice_text' => trim($questionData['falseText']),
                                'is_correct' => (string)$questionData['correctAnswer'] === 'false',
                            ]);
                        }
                        break;

                    case 'short_answer':
                    case 'long_answer':
                        // For text-based questions, we don't need choices
                        // The answer will be stored when students submit
                        break;
                }
            }

            DB::commit();

            $action = $request->input('action') === 'publish' ? 'published' : 'saved';
            
            // Get course code for redirect
            $course = Course::find($request->course_id);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Assessment {$action} successfully!",
                    'quiz_id' => $quiz->id,
                    'course_code' => $course ? $course->course_code : null
                ]);
            }
            
            return redirect()->back()->with('success', "Assessment {$action} successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save assessment: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save assessment: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing assessment
     */
    public function update(Request $request, $id)
    {
        $quiz = Quiz::findOrFail($id);

        // Decode questions JSON for validation (same as store)
        $questionsData = json_decode($request->questions, true);
        if (!is_array($questionsData)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid questions data format.',
                    'errors' => ['questions' => ['Questions data is invalid']]
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['questions' => 'Questions data is invalid'])
                ->withInput()
                ->with('error', 'Invalid questions data format.');
        }

        // Validate the request (same as store)
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'type' => 'required|in:quiz,exam',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'closing_schedule' => 'nullable|date',
            'timer_hours' => 'nullable|integer|min:0|max:23',
            'timer_minutes' => 'nullable|integer|min:0|max:59',
            'submission_limit' => 'nullable|integer|min:1',
            'questions' => 'required|string', // Changed to string since we're sending JSON
        ]);

        // Add custom validation for questions array
        $validator->after(function ($validator) use ($questionsData) {
            if (empty($questionsData)) {
                $validator->errors()->add('questions', 'At least one question is required.');
                return;
            }

            foreach ($questionsData as $index => $question) {
                if (empty($question['text'])) {
                    $validator->errors()->add("questions.{$index}.text", 'Question text is required.');
                }
                
                if (!in_array($question['type'], ['multiple_choice', 'true_false', 'short_answer', 'long_answer'])) {
                    $validator->errors()->add("questions.{$index}.type", 'Invalid question type.');
                }
                
                if (!isset($question['points']) || $question['points'] < 1) {
                    $validator->errors()->add("questions.{$index}.points", 'Points must be at least 1.');
                }
                
                if ($question['type'] === 'multiple_choice') {
                    if (empty($question['options']) || count($question['options']) < 2) {
                        $validator->errors()->add("questions.{$index}.options", 'Multiple choice questions must have at least 2 options.');
                    }
                    
                    $validOptions = array_filter($question['options'], function($opt) {
                        return !empty(trim($opt));
                    });
                    
                    if (count($validOptions) < 2) {
                        $validator->errors()->add("questions.{$index}.options", 'Multiple choice questions must have at least 2 non-empty options.');
                    }
                }

                // Short answer questions must have acceptedAnswers or modelAnswer defined
                if ($question['type'] === 'short_answer') {
                    // Support both old (modelAnswer) and new (acceptedAnswers) formats
                    $hasAnswers = false;
                    
                    if (isset($question['acceptedAnswers']) && is_array($question['acceptedAnswers'])) {
                        $validAnswers = array_filter($question['acceptedAnswers'], function($ans) {
                            return !empty(trim($ans));
                        });
                        $hasAnswers = count($validAnswers) > 0;
                    } elseif (isset($question['modelAnswer']) && !empty(trim($question['modelAnswer']))) {
                        $hasAnswers = true;
                    }
                    
                    if (!$hasAnswers) {
                        $validator->errors()->add("questions.{$index}.acceptedAnswers", 'At least one answer is required for short answer questions.');
                    }
                }
            }
        });

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please fix the validation errors and try again.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        try {
            DB::beginTransaction();

            // Determine status based on action
            $status = $request->input('action') === 'publish' ? 'Published' : 'Draft';

            // Update the quiz
            $quiz->update([
                'course_id' => $request->course_id,
                'quiz_title' => $request->title,
                'description' => $request->description,
                'status' => $status,
                'end_date' => $request->closing_schedule ?: $quiz->end_date,
                'timer_hours' => $request->timer_hours ?: 0,
                'timer_minutes' => $request->timer_minutes ?: 0,
                'submission_limit' => $request->submission_limit ?: 1,
            ]);

            // Delete existing related records in the correct order to satisfy FKs
            $existingQuestionIds = $quiz->questions()->pluck('id');

            // 1. Delete learner answers linked to these questions
            Answer::whereIn('question_id', $existingQuestionIds)->delete();

            // 2. Delete choices linked to these questions
            Choice::whereIn('question_id', $existingQuestionIds)->delete();

            // 3. Delete the questions themselves
            Question::whereIn('id', $existingQuestionIds)->delete();

            // Process questions (use already decoded $questionsData)
            foreach ($questionsData as $questionData) {
                // Prepare model_answer field
                $modelAnswer = null;
                if ($questionData['type'] === 'short_answer') {
                    // For short answer, store acceptedAnswers as JSON, or fallback to modelAnswer
                    if (isset($questionData['acceptedAnswers']) && is_array($questionData['acceptedAnswers'])) {
                        $validAnswers = array_filter($questionData['acceptedAnswers'], function($ans) {
                            return !empty(trim($ans));
                        });
                        $modelAnswer = json_encode(array_values($validAnswers));
                    } elseif (isset($questionData['modelAnswer'])) {
                        $modelAnswer = $questionData['modelAnswer'];
                    }
                } else {
                    $modelAnswer = $questionData['modelAnswer'] ?? null;
                }
                
                // Handle image upload
                $imagePath = null;
                $imageName = null;
                if (isset($questionData['imageData']) && $questionData['imageData']) {
                    $imagePath = $this->saveBase64Image($questionData['imageData'], $questionData['imageName'] ?? 'image.jpg');
                    $imageName = $questionData['imageName'] ?? null;
                }
                
                // Create the question
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['text'],
                    'model_answer' => $modelAnswer,
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                    'image_path' => $imagePath,
                    'image_name' => $imageName,
                ]);

                // Handle different question types (same logic as store)
                switch ($questionData['type']) {
                    case 'multiple_choice':
                        if (isset($questionData['options']) && is_array($questionData['options'])) {
                            foreach ($questionData['options'] as $index => $optionText) {
                                if (!empty(trim($optionText))) {
                                    Choice::create([
                                        'question_id' => $question->id,
                                        'choice_text' => trim($optionText),
                                        'is_correct' => $questionData['correctAnswer'] == $index,
                                    ]);
                                }
                            }
                        }
                        break;

                    case 'true_false':
                        if (isset($questionData['trueText']) && !empty(trim($questionData['trueText']))) {
                            Choice::create([
                                'question_id' => $question->id,
                                'choice_text' => trim($questionData['trueText']),
                                'is_correct' => $questionData['correctAnswer'] === 'true',
                            ]);
                        }
                        if (isset($questionData['falseText']) && !empty(trim($questionData['falseText']))) {
                            Choice::create([
                                'question_id' => $question->id,
                                'choice_text' => trim($questionData['falseText']),
                                'is_correct' => $questionData['correctAnswer'] === 'false',
                            ]);
                        }
                        break;
                }
            }

            DB::commit();

            $action = $request->input('action') === 'publish' ? 'updated and published' : 'updated';
            
            // Get course code for redirect
            $course = Course::find($request->course_id);
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Assessment {$action} successfully!",
                    'quiz_id' => $quiz->id,
                    'course_code' => $course ? $course->course_code : null
                ]);
            }
            
            return redirect()->back()->with('success', "Assessment {$action} successfully!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update assessment: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update assessment: ' . $e->getMessage());
        }
    }

    /**
     * Delete an assessment
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $quiz = Quiz::findOrFail($id);
            $courseCode = $quiz->course->course_code;
            
            // Get all question IDs for this quiz
            $questionIds = Question::where('quiz_id', $quiz->id)->pluck('id');
            
            // Delete in correct order to avoid foreign key constraints:
            // 1. Delete answers (references questions and quiz)
            Answer::where('quiz_id', $quiz->id)->delete();
            
            // 2. Delete quiz results (references quiz)
            QuizResult::where('quiz_id', $quiz->id)->delete();
            
            // 3. Delete choices (references questions)
            Choice::whereIn('question_id', $questionIds)->delete();
            
            // 4. Delete questions (references quiz)
            Question::where('quiz_id', $quiz->id)->delete();
            
            // 5. Finally delete the quiz
            $quiz->delete();
            
            DB::commit();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assessment deleted successfully',
                    'course_code' => $courseCode
                ]);
            }
            
            return redirect()->route('implementor.course-information', $courseCode)
                ->with('success', 'Assessment deleted successfully');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete assessment: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to delete assessment: ' . $e->getMessage());
        }
    }
}
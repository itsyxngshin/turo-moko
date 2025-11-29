<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssessmentBuilderController extends Controller
{
    /**
     * Show the assessment builder form
     */
    public function create()
    {
        $courses = Course::all();
        return view('implementor.assessment-builder', compact('courses'));
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
            }
        });

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the validation errors and try again.');
        }

        try {
            DB::beginTransaction();

            // Determine status based on action
            $status = $request->input('action') === 'publish' ? 'Published' : 'Draft';

            // Create the quiz
            $quiz = Quiz::create([
                'course_id' => $request->course_id,
                'quiz_title' => $request->title,
                'description' => $request->description,
                'status' => $status,
                'start_date' => now(),
                'end_date' => $request->closing_schedule ?: now()->addDays(7),
                'timer_hours' => $request->timer_hours,
                'timer_minutes' => $request->timer_minutes,
                'submission_limit' => $request->submission_limit,
                'visibility' => true,
            ]);

            // Process questions (already decoded above)
            $questions = $questionsData;
            
            foreach ($questions as $questionData) {
                // Create the question
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['text'],
                    'model_answer' => $questionData['modelAnswer'] ?? null,
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
            
            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Assessment {$action} successfully! Quiz ID: {$quiz->id}",
                    'quiz_id' => $quiz->id
                ]);
            }
            
            return redirect()->back()->with('success', "Assessment {$action} successfully! Quiz ID: {$quiz->id}");

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
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string|max:1000',
            'questions.*.type' => 'required|in:multiple_choice,true_false,short_answer,long_answer',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array|min:2',
            'questions.*.options.*' => 'required_if:questions.*.type,multiple_choice|string|max:500',
            'questions.*.trueText' => 'required_if:questions.*.type,true_false|string|max:500',
            'questions.*.falseText' => 'required_if:questions.*.type,true_false|string|max:500',
        ]);

        if ($validator->fails()) {
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
                'timer_hours' => $request->timer_hours,
                'timer_minutes' => $request->timer_minutes,
                'submission_limit' => $request->submission_limit,
            ]);

            // Delete existing questions and choices
            $quiz->questions()->delete();

            // Process questions (same as store)
            $questions = json_decode($request->questions, true);
            
            foreach ($questions as $questionData) {
                // Create the question
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'question_text' => $questionData['text'],
                    'model_answer' => $questionData['modelAnswer'] ?? null,
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
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
            return redirect()->back()->with('success', "Assessment {$action} successfully! Quiz ID: {$quiz->id}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update assessment: ' . $e->getMessage());
        }
    }
}
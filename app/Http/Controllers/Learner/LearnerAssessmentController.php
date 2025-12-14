<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuizResult;
use App\Models\CourseEnrollee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LearnerAssessmentController extends Controller
{
    /**
     * REMOVED: Display list of available assessments for enrolled courses
     * Assessments are now accessed through course pages, not as a standalone page
     * 
     * This method has been disabled - assessments should be integrated into course detail pages
     */
    /*
    public function index()
    {
        $user = Auth::user();
        
        // Get courses the learner is enrolled in
        $enrolledCourseIds = CourseEnrollee::where('enrollee_id', $user->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->pluck('course_id');

        // Get published quizzes for those courses
        $assessments = Quiz::with(['course', 'questions'])
            ->whereIn('course_id', $enrolledCourseIds)
            ->where('status', 'Published')
            ->where('visibility', true)
            ->where('start_date', '<=', now())
            ->orderBy('end_date', 'asc')
            ->get()
            ->map(function ($quiz) use ($user) {
                $enrollee = CourseEnrollee::where('enrollee_id', $user->id)
                    ->where('course_id', $quiz->course_id)
                    ->first();

                // Check if learner has submitted
                $submission = QuizResult::where('quiz_id', $quiz->id)
                    ->where('course_enrollee_id', $enrollee->id ?? null)
                    ->first();

                $submissionCount = QuizResult::where('quiz_id', $quiz->id)
                    ->where('course_enrollee_id', $enrollee->id ?? null)
                    ->count();

                $totalPoints = $quiz->questions->sum('points');
                $questionCount = $quiz->questions->count();

                return [
                    'id' => $quiz->id,
                    'title' => $quiz->quiz_title,
                    'description' => $quiz->description,
                    'course_name' => $quiz->course->course_title ?? 'Unknown Course',
                    'course_code' => $quiz->course->course_code ?? '',
                    'end_date' => $quiz->end_date,
                    'is_overdue' => $quiz->end_date < now(),
                    'total_points' => $totalPoints,
                    'question_count' => $questionCount,
                    'timer_hours' => $quiz->timer_hours,
                    'timer_minutes' => $quiz->timer_minutes,
                    'submission_limit' => $quiz->submission_limit,
                    'submission_count' => $submissionCount,
                    'can_submit' => !$quiz->submission_limit || $submissionCount < $quiz->submission_limit,
                    'has_submitted' => $submission !== null,
                    'status' => $submission ? $submission->status : null,
                    'score' => $submission ? $submission->score : null,
                ];
            });

        return view('learner.assessments', compact('assessments'));
    }
    */

    /**
     * Display a specific assessment for taking
     */
    public function show($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['course', 'questions.choices'])->findOrFail($quizId);

        // Check if learner is enrolled in the course
        $enrollee = CourseEnrollee::where('enrollee_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['Active', 'Completed'])
            ->first();

        if (!$enrollee) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Check if quiz is published and accessible
        if ($quiz->status !== 'Published' || !$quiz->visibility) {
            abort(403, 'This assessment is not available.');
        }

        if ($quiz->start_date > now()) {
            abort(403, 'This assessment has not started yet.');
        }

        // Check submission limit
        $submissionCount = QuizResult::where('quiz_id', $quiz->id)
            ->where('course_enrollee_id', $enrollee->id)
            ->count();

        if ($quiz->submission_limit && $submissionCount >= $quiz->submission_limit) {
            return redirect()->route('learner.assessment.result', $quiz->id)
                ->with('error', 'You have reached the submission limit for this assessment.');
        }

        // Check if overdue
        $isOverdue = $quiz->end_date < now();

        // Prepare questions data
        $questions = $quiz->questions->map(function ($question, $index) {
            return [
                'id' => $question->id,
                'number' => $index + 1,
                'text' => $question->question_text,
                'type' => $question->type,
                'points' => $question->points,
                'choices' => $question->choices->map(function ($choice) {
                    return [
                        'id' => $choice->id,
                        'text' => $choice->choice_text,
                    ];
                }),
            ];
        });

        $totalPoints = $quiz->questions->sum('points');
        $timerDuration = null;
        if ($quiz->timer_hours || $quiz->timer_minutes) {
            $timerDuration = ($quiz->timer_hours * 60) + $quiz->timer_minutes; // in minutes
        }

        return view('learner.assessment', compact('quiz', 'questions', 'totalPoints', 'timerDuration', 'isOverdue', 'submissionCount'));
    }

    /**
     * Submit assessment answers
     */
    public function submit(Request $request, $quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['questions.choices'])->findOrFail($quizId);

        // Check if learner is enrolled
        $enrollee = CourseEnrollee::where('enrollee_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['Active', 'Completed'])
            ->first();

        if (!$enrollee) {
            return response()->json(['success' => false, 'message' => 'You are not enrolled in this course.'], 403);
        }

        // Check submission limit
        $submissionCount = QuizResult::where('quiz_id', $quiz->id)
            ->where('course_enrollee_id', $enrollee->id)
            ->count();

        if ($quiz->submission_limit && $submissionCount >= $quiz->submission_limit) {
            return response()->json(['success' => false, 'message' => 'Submission limit reached.'], 403);
        }

        try {
            DB::beginTransaction();

            // Get current attempt number for this student on this quiz
            $attemptNumber = QuizResult::where('quiz_id', $quiz->id)
                ->where('course_enrollee_id', $enrollee->id)
                ->max('attempt_number') ?? 0;
            $attemptNumber++; // Increment for new attempt

            $totalScore = 0;
            $hasUngradedAnswers = false;

            // Process each answer
            foreach ($quiz->questions as $question) {
                $answerKey = 'answer_' . $question->id;
                $answerValue = $request->input($answerKey);

                $answerData = [
                    'question_id' => $question->id,
                    'quiz_id' => $quiz->id,
                    'course_enrollee_id' => $enrollee->id,
                ];

                switch ($question->type) {
                    case 'multiple_choice':
                    case 'true_false':
                        // Check if the selected choice is correct
                        $selectedChoice = $question->choices->firstWhere('id', $answerValue);
                        
                        if ($selectedChoice) {
                            $answerData['option_id'] = $selectedChoice->id;
                            $answerData['answer_text'] = $selectedChoice->choice_text;
                            $answerData['is_correct'] = $selectedChoice->is_correct;
                            $answerData['points'] = $selectedChoice->is_correct ? $question->points : 0;
                            $totalScore += $answerData['points'];
                        } else {
                            // No answer provided
                            $answerData['answer_text'] = null;
                            $answerData['is_correct'] = false;
                            $answerData['points'] = 0;
                        }
                        break;

                    case 'short_answer':
                        // Auto-grade short answer based on model answer
                        $studentAnswerRaw = $answerValue ?? '';
                        $answerData['answer_text'] = $studentAnswerRaw;

                        $modelAnswerRaw = (string) ($question->model_answer ?? '');
                        $studentNormalized = trim(mb_strtolower($studentAnswerRaw));
                        $modelNormalized = trim(mb_strtolower($modelAnswerRaw));

                        // If no model answer is defined (legacy quizzes), fall back to manual grading
                        if ($modelNormalized === '') {
                            $answerData['is_correct'] = false;
                            $answerData['points'] = -1; // ungraded
                            $hasUngradedAnswers = true;
                        } else {
                            // Treat empty student answer as incorrect with 0 points
                            if ($studentNormalized !== '' && $studentNormalized === $modelNormalized) {
                                $answerData['is_correct'] = true;
                                $answerData['points'] = $question->points;
                                $totalScore += $answerData['points'];
                            } else {
                                $answerData['is_correct'] = false;
                                $answerData['points'] = 0;
                            }
                        }
                        break;

                    case 'long_answer':
                        // Store text answer, mark for manual grading
                        $answerData['answer_text'] = $answerValue ?? '';
                        $answerData['is_correct'] = false;
                        $answerData['points'] = -1; // -1 indicates ungraded
                        $hasUngradedAnswers = true;
                        break;
                }

                Answer::create($answerData);
            }

            // Create quiz result
            $quizResult = QuizResult::create([
                'quiz_id' => $quiz->id,
                'course_enrollee_id' => $enrollee->id,
                'attempt_number' => $attemptNumber,
                'score' => $totalScore,
                'status' => $hasUngradedAnswers ? 'Pending' : 'Checked',
                'remarks' => $hasUngradedAnswers ? 'Awaiting manual grading' : 'Auto-graded',
                'checked_at' => $hasUngradedAnswers ? null : now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Assessment submitted successfully!',
                'result_id' => $quizResult->id,
                'redirect_url' => route('learner.assessment.result', $quiz->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Assessment submission error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit assessment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display assessment result
     */
    public function result($quizId)
    {
        $user = Auth::user();
        $quiz = Quiz::with(['course', 'questions.choices'])->findOrFail($quizId);

        // Check if learner is enrolled
        $enrollee = CourseEnrollee::where('enrollee_id', $user->id)
            ->where('course_id', $quiz->course_id)
            ->whereIn('status', ['Active', 'Completed'])
            ->first();

        if (!$enrollee) {
            abort(403, 'You are not enrolled in this course.');
        }

        // Get the most recent submission
        $quizResult = QuizResult::where('quiz_id', $quiz->id)
            ->where('course_enrollee_id', $enrollee->id)
            ->latest()
            ->first();

        if (!$quizResult) {
            return redirect()->route('learner.assessment.show', $quiz->id)
                ->with('error', 'You have not submitted this assessment yet.');
        }

        // Get all answers
        $answers = Answer::where('quiz_id', $quiz->id)
            ->where('course_enrollee_id', $enrollee->id)
            ->with(['question.choices'])
            ->get()
            ->keyBy('question_id');

        $totalPoints = $quiz->questions->sum('points');
        $percentage = $totalPoints > 0 ? round(($quizResult->score / $totalPoints) * 100, 1) : 0;

        // Prepare questions with answers
        $questionsWithAnswers = $quiz->questions->map(function ($question) use ($answers) {
            $answer = $answers->get($question->id);
            $correctChoice = $question->choices->firstWhere('is_correct', true);

            return [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->type,
                'points' => $question->points,
                'model_answer' => $question->model_answer,
                'your_answer' => $answer ? ($answer->choice->choice_text ?? $answer->answer_text) : 'No answer',
                'correct_answer' => $correctChoice ? $correctChoice->choice_text : $question->model_answer,
                'is_correct' => $answer ? $answer->is_correct : false,
                'points_earned' => $answer && $answer->points >= 0 ? $answer->points : 0,
                'is_graded' => $answer && $answer->points >= 0,
            ];
        });

        return view('learner.assessment-result', compact(
            'quiz',
            'quizResult',
            'totalPoints',
            'percentage',
            'questionsWithAnswers'
        ));
    }
}


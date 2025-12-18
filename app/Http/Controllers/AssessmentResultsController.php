<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizResult;
use App\Models\CourseEnrollee;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AssessmentResultsController extends Controller
{
    /**
     * Display list of quizzes with response counts
     */
    public function index(Request $request)
    {
        $courseId = $request->get('course_id');
        $course = $courseId ? Course::find($courseId) : null;

        $quizzes = $this->loadQuizzes($courseId);

        return view('implementor.assessment-results', compact('quizzes', 'courseId', 'course'));
    }

    /**
     * Assignment submissions page.
     */
    public function assignmentsIndex(Request $request)
    {
        $courseId = $request->get('course_id');
        $course = $courseId ? Course::find($courseId) : null;
        $assignmentsWithSubs = $this->loadAssignmentsWithSubmissions($courseId);

        return view('implementor.assignment-submissions', compact('assignmentsWithSubs', 'courseId', 'course'));
    }

    public function gradeAssignment(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'grade' => 'nullable|numeric|min:0|max:9999',
            'feedback' => 'nullable|string',
        ]);

        $submission = Submission::findOrFail($validated['submission_id']);
        $submission->update([
            'grade' => $validated['grade'] ?? null,
            'feedback' => $validated['feedback'] ?? null,
            'graded_by' => auth()->id(),
            'graded_at' => now(),
        ]);

        return redirect()->back()->with('swal', [
            'icon' => 'success',
            'title' => 'Success!',
            'text' => 'Assignment graded successfully.',
        ]);
    }

    private function loadAssignmentsWithSubmissions($courseId)
    {
        if (!$courseId) {
            return collect();
        }

        $assignments = Assignment::where('course_id', $courseId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $assignments->map(function ($assignment) {
            $subs = Submission::where('assignment_id', $assignment->id)
                ->with(['enrollee.user.profile'])
                ->orderBy('created_at', 'desc')
                ->get();

            $submissions = $subs->map(function ($submission) {
                $user = $submission->enrollee->user ?? null;
                $profile = $user->profile ?? null;
                $textSubmission = $submission->instruction ?? null;
                $attachmentUrl = null;
                if ($submission->attachment && Storage::exists($submission->attachment)) {
                    $attachmentUrl = Storage::url($submission->attachment);
                }

                $submissionStatus = $submission->grade !== null ? 'Graded' : 'Not Graded';

                return [
                    'id' => $submission->id,
                    'student_name' => $profile
                        ? trim($profile->first_name . ' ' . $profile->last_name)
                        : ($user->username ?? 'Unknown Student'),
                    'email' => $user->email ?? '',
                    'submitted_at' => $submission->created_at?->format('M d, Y h:i A'),
                    'status' => $submissionStatus,
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback,
                    'text_submission' => $textSubmission,
                    'attachment_url' => $attachmentUrl,
                    'attachment_name' => $submission->attachment_original_name,
                ];
            });

            return [
                'id' => $assignment->id,
                'title' => $assignment->title,
                'submissions' => $submissions,
            ];
        });
    }

    /**
     * Get detailed results for a specific quiz (AJAX)
     */
    public function show($quizId)
    {
        $quiz = Quiz::with(['course', 'questions.choices'])->findOrFail($quizId);
        
        // Get ALL enrolled students for this course
        $enrollees = CourseEnrollee::where('course_id', $quiz->course_id)
            ->whereIn('status', ['Active', 'Completed'])
            ->with(['user.profile'])
            ->get();
        
        // Get only the latest attempt for each student who has submitted
        $latestAttempts = QuizResult::where('quiz_id', $quizId)
            ->select('course_enrollee_id', DB::raw('MAX(attempt_number) as max_attempt'))
            ->groupBy('course_enrollee_id')
            ->get();

        // Get the full results for those latest attempts only
        $results = QuizResult::where('quiz_id', $quizId)
            ->whereIn('course_enrollee_id', $latestAttempts->pluck('course_enrollee_id'))
            ->where(function($query) use ($latestAttempts) {
                foreach ($latestAttempts as $attempt) {
                    $query->orWhere(function($q) use ($attempt) {
                        $q->where('course_enrollee_id', $attempt->course_enrollee_id)
                          ->where('attempt_number', $attempt->max_attempt);
                    });
                }
            })
            ->with(['enrollee.user.profile'])
            ->get();
        
        // Create a map of enrollee_id => result for quick lookup
        $resultsMap = $results->keyBy('course_enrollee_id');

        // Calculate stats
        $totalPoints = $quiz->questions->sum('points');
        $totalSubmissions = $results->count();
        $gradedCount = $results->where('status', 'Checked')->count();
        $pendingCount = $results->where('status', 'Pending')->count();
        $enrolledCount = $enrollees->count();
        
        $gradedResults = $results->where('status', 'Checked');
        $averageScore = $gradedResults->count() > 0 
            ? round($gradedResults->avg('score'), 1) 
            : 0;
        $averagePercentage = $totalPoints > 0 && $gradedResults->count() > 0
            ? round(($averageScore / $totalPoints) * 100, 1)
            : 0;
        $completionRate = $enrolledCount > 0
            ? round(($totalSubmissions / $enrolledCount) * 100, 1)
            : null;

        $passThreshold = 60;
        $passCount = 0;
        $scoreDistribution = [
            'excellent' => 0,       // 90-100
            'good' => 0,            // 70-89
            'needs_improvement' => 0, // 60-69
            'fail' => 0,            // <60
        ];

        foreach ($gradedResults as $gradedResult) {
            if ($totalPoints <= 0) {
                continue;
            }

            $percentage = round((($gradedResult->score ?? 0) / $totalPoints) * 100, 1);

            if ($percentage >= $passThreshold) {
                $passCount++;
            }

            if ($percentage >= 90) {
                $scoreDistribution['excellent']++;
            } elseif ($percentage >= 70) {
                $scoreDistribution['good']++;
            } elseif ($percentage >= 60) {
                $scoreDistribution['needs_improvement']++;
            } else {
                $scoreDistribution['fail']++;
            }
        }

        $failCount = $gradedCount > 0 ? $gradedCount - $passCount : 0;
        $passRate = $gradedCount > 0 ? round(($passCount / $gradedCount) * 100, 1) : 0;

        // Preload answers grouped by question for analytics
        $answersByQuestion = Answer::where('quiz_id', $quiz->id)
            ->with('question')
            ->get()
            ->groupBy('question_id');

        // Build student submissions data - include ALL enrolled students
        $submissions = $enrollees->map(function ($enrollee) use ($resultsMap, $quiz, $totalPoints) {
            $result = $resultsMap->get($enrollee->id);
            $user = $enrollee->user ?? null;
            $profile = $user->profile ?? null;
            
            $studentName = $profile 
                ? trim($profile->first_name . ' ' . $profile->last_name) 
                : ($user->username ?? $user->email ?? 'Unknown Student');
            
            // If student hasn't submitted, return minimal data
            if (!$result) {
                return [
                    'id' => null,
                    'course_enrollee_id' => $enrollee->id,
                    'student_name' => $studentName,
                    'email' => $user->email ?? '',
                    'submitted_at' => null,
                    'score' => 0,
                    'total_points' => $totalPoints,
                    'percentage' => 0,
                    'status' => 'Not Submitted',
                    'has_submission' => false,
                    'has_ungraded' => false,
                    'answers' => [],
                ];
            }
            
            // Get total number of attempts for this student
            $totalAttempts = QuizResult::where('quiz_id', $quiz->id)
                ->where('course_enrollee_id', $enrollee->id)
                ->count();
            
            // Get student's answers for this quiz
            $answers = Answer::where('quiz_id', $quiz->id)
                ->where('course_enrollee_id', $enrollee->id)
                ->with(['question', 'choice'])
                ->get();

            // Check if there are any ungraded long-answer questions (points = -1 means ungraded)
            $hasUngradedEssays = $answers->filter(function ($answer) {
                return in_array($answer->question->type, ['long_answer']) 
                    && $answer->points < 0;
            })->count() > 0;

            return [
                'id' => $result->id,
                'course_enrollee_id' => $enrollee->id,
                'attempt_number' => $result->attempt_number,
                'total_attempts' => $totalAttempts,
                'student_name' => $studentName,
                'email' => $user->email ?? '',
                'submitted_at' => $result->created_at->format('M d, Y h:i A'),
                'submitted_at_timestamp' => $result->created_at->timestamp,
                'score' => $result->score ?? 0,
                'total_points' => $totalPoints,
                'percentage' => $totalPoints > 0 ? round((($result->score ?? 0) / $totalPoints) * 100, 1) : 0,
                'status' => $result->status,
                'has_submission' => true,
                'has_ungraded' => $hasUngradedEssays,
                'answers' => $answers->map(function ($answer) {
                    $question = $answer->question;
                    $correctChoice = $question->choices->where('is_correct', true)->first();
                    
                    return [
                        'id' => $answer->id,
                        'question_id' => $question->id,
                        'question_text' => $question->question_text,
                        'question_type' => $question->type,
                        'question_points' => $question->points,
                        'model_answer' => $question->model_answer,
                        'answer_text' => $answer->answer_text,
                        'choice_text' => $answer->choice->choice_text ?? null,
                        'correct_answer' => $correctChoice->choice_text ?? $question->model_answer,
                        'is_correct' => $answer->is_correct,
                        'points_earned' => $answer->points >= 0 ? $answer->points : 0,
                        'needs_grading' => in_array($question->type, ['long_answer']) 
                            && $answer->points < 0,
                    ];
                }),
            ];
        });

        // Questions breakdown for reference + stats
        $questions = $quiz->questions->map(function ($question) {
            return [
                'id' => $question->id,
                'text' => $question->question_text,
                'type' => $question->type,
                'points' => $question->points,
                'model_answer' => $question->model_answer,
            ];
        });

        $questionStats = $quiz->questions->values()->map(function ($question, $index) use ($answersByQuestion) {
            $questionAnswers = $answersByQuestion->get($question->id, collect());
            $gradedAttempts = $questionAnswers->where('points', '>=', 0);
            $gradedCount = $gradedAttempts->count();

            $fullyCorrect = $gradedAttempts->filter(function ($answer) use ($question) {
                if (in_array($question->type, ['multiple_choice', 'true_false'])) {
                    return (bool) $answer->is_correct;
                }

                return $answer->points >= $question->points;
            })->count();

            $percentCorrect = $gradedCount > 0
                ? round(($fullyCorrect / $gradedCount) * 100, 1)
                : 0;

            return [
                'question_id' => $question->id,
                'question_number' => $index + 1,
                'question_text' => $question->question_text,
                'type' => $question->type,
                'points' => $question->points,
                'graded_attempts' => $gradedCount,
                'percent_correct' => $percentCorrect,
            ];
        });

        return response()->json([
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->quiz_title,
                'course_name' => $quiz->course->course_title ?? 'No Course',
                'description' => $quiz->description,
                'total_points' => $totalPoints,
            ],
            'stats' => [
                'total_submissions' => $totalSubmissions,
                'graded_count' => $gradedCount,
                'pending_count' => $pendingCount,
                'average_score' => $averageScore,
                'average_percentage' => $averagePercentage,
                'total_points' => $totalPoints,
                'enrolled_count' => $enrolledCount,
                'completion_rate' => $completionRate,
                'pass_rate' => $passRate,
                'pass_count' => $passCount,
                'fail_count' => $failCount,
                'pass_threshold' => $passThreshold,
            ],
            'score_distribution' => $scoreDistribution,
            'question_stats' => $questionStats,
            'submissions' => $submissions,
            'questions' => $questions,
        ]);
    }

    /**
     * Save grade for an essay/short answer question
     */
    public function grade(Request $request)
    {
        $request->validate([
            'answer_id' => 'required|exists:answers,id',
            'points' => 'required|numeric|min:0',
        ]);

        $answer = Answer::with('question')->findOrFail($request->answer_id);
        
        // Validate points don't exceed question max
        if ($request->points > $answer->question->points) {
            return response()->json([
                'success' => false,
                'message' => 'Points cannot exceed maximum for this question (' . $answer->question->points . ')'
            ], 422);
        }

        // Update the answer with points
        $answer->update([
            'points' => $request->points,
            'is_correct' => $request->points > 0,
        ]);

        // Recalculate total score for the quiz result
        $this->recalculateQuizResult($answer->quiz_id, $answer->course_enrollee_id);

        return response()->json([
            'success' => true,
            'message' => 'Grade saved successfully',
            'points' => $request->points,
        ]);
    }

    /**
     * Recalculate quiz result score after grading
     */
    private function recalculateQuizResult($quizId, $courseEnrolleeId)
    {
        $quiz = Quiz::with('questions')->findOrFail($quizId);
        
        // Get the latest attempt number for this student
        $latestAttempt = QuizResult::where('quiz_id', $quizId)
            ->where('course_enrollee_id', $courseEnrolleeId)
            ->max('attempt_number');
        
        // Get the latest quiz result
        $quizResult = QuizResult::where('quiz_id', $quizId)
            ->where('course_enrollee_id', $courseEnrolleeId)
            ->where('attempt_number', $latestAttempt)
            ->first();
            
        if (!$quizResult) {
            return;
        }
        
        // Get all answers for this submission (latest attempt only)
        // Get the most recent N answers where N = number of questions
        $questionCount = $quiz->questions->count();
        
        $answers = Answer::where('quiz_id', $quizId)
            ->where('course_enrollee_id', $courseEnrolleeId)
            ->orderBy('created_at', 'desc')
            ->limit($questionCount)
            ->get();

        // Calculate total score (ignore ungraded entries marked as < 0)
        $totalScore = $answers->sum(function ($answer) {
            return $answer->points >= 0 ? $answer->points : 0;
        });
        
        // Check if all questions are graded (points >= 0 means graded)
        $totalQuestions = $quiz->questions->count();
        $gradedAnswers = $answers->filter(fn($a) => $a->points >= 0)->count();
        $allGraded = $gradedAnswers >= $totalQuestions;

        // Update quiz result
        $quizResult->update([
            'score' => $totalScore,
            'status' => $allGraded ? 'Checked' : 'Pending',
            'checked_at' => $allGraded ? now() : null,
            'remarks' => $allGraded ? 'Grading complete' : 'Partially graded',
        ]);
    }

    /**
     * Export quiz results to CSV
     */
    public function export($quizId)
    {
        $quiz = Quiz::with(['questions'])->findOrFail($quizId);
        
        $results = QuizResult::where('quiz_id', $quizId)
            ->with(['enrollee.user.profile'])
            ->get();

        $filename = 'assessment_results_' . $quiz->id . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($quiz, $results) {
            $file = fopen('php://output', 'w');
            
            // CSV Header row
            $headerRow = ['Student Name', 'Email', 'Submitted At', 'Total Score', 'Percentage', 'Status'];
            
            // Add question columns
            foreach ($quiz->questions as $question) {
                $headerRow[] = 'Q' . $question->id . ': ' . substr($question->question_text, 0, 30) . '...';
            }
            
            fputcsv($file, $headerRow);

            // Data rows
            foreach ($results as $result) {
                $user = $result->enrollee->user ?? null;
                $profile = $user->profile ?? null;
                $totalPoints = $quiz->questions->sum('points');
                
                $studentName = $profile 
                    ? trim($profile->first_name . ' ' . $profile->last_name) 
                    : ($user->username ?? 'Unknown');
                
                $row = [
                    $studentName,
                    $user->email ?? '',
                    $result->created_at->format('Y-m-d H:i:s'),
                    $result->score . '/' . $totalPoints,
                    $totalPoints > 0 ? round(($result->score / $totalPoints) * 100, 1) . '%' : '0%',
                    $result->status,
                ];

                // Get answers for each question
                $answers = Answer::where('quiz_id', $quiz->id)
                    ->where('course_enrollee_id', $result->course_enrollee_id)
                    ->get()
                    ->keyBy('question_id');

                foreach ($quiz->questions as $question) {
                    $answer = $answers->get($question->id);
                    if ($answer) {
                        $points = $answer->points >= 0 ? $answer->points : 'Ungraded';
                        $row[] = $points . '/' . $question->points;
                    } else {
                        $row[] = 'No answer';
                    }
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Helper: load quizzes with submission counts and averages.
     */
    private function loadQuizzes($courseId)
    {
        $query = Quiz::with(['course', 'questions'])
            ->withCount(['results as total_submissions'])
            ->withCount(['results as pending_grading' => function ($query) {
                $query->where('status', 'Pending');
            }]);
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        return $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($quiz) {
                $totalPoints = $quiz->questions->sum('points');
                
                $gradedResults = QuizResult::where('quiz_id', $quiz->id)
                    ->where('status', 'Checked')
                    ->get();
                
                $averageScore = $gradedResults->count() > 0 
                    ? round($gradedResults->avg('score'), 1) 
                    : null;

                return [
                    'id' => $quiz->id,
                    'title' => $quiz->quiz_title,
                    'course_name' => $quiz->course->course_title ?? 'No Course',
                    'total_submissions' => $quiz->total_submissions,
                    'pending_grading' => $quiz->pending_grading,
                    'total_points' => $totalPoints,
                    'average_score' => $averageScore,
                    'status' => $quiz->status,
                    'created_at' => $quiz->created_at->format('M d, Y'),
                ];
            });
    }
}

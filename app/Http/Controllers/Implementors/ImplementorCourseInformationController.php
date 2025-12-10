<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Assignment;
use App\Models\Resource;
use App\Models\User;
use App\Models\ProgramEvaluation;
use App\Models\ImplementerEvaluation;
use App\Models\Quiz;
use App\Models\Announcement;
use App\Models\CourseEnrollee;
use App\Models\EvaluationQuestion;
use App\Models\RatingResponse;
use App\Models\CommentResponse;
use App\Models\ImplementerRatingResponse;
use App\Models\ImplementerCommentResponse;
use Illuminate\Support\Collection;

class ImplementorCourseInformationController extends Controller
{
    public function show(Course $course)
    {
        // Get logged-in user
        $implementor = auth()->user();

        // Ensure the user is an implementor
        if (!$implementor || $implementor->role_id != 2) {
            abort(403, 'Unauthorized: Only implementors can access this page.');
        }

        // Ensure the implementor OWNS this course
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized: You do not own this course.');
        }

        // Fetch related data
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch assignments for this course
        $assignments = Assignment::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get existing evaluation entry for this course (master record with null enrollee_id)
        // For implementor view, we show ONE evaluation item per course if it exists
        $masterEval = ProgramEvaluation::where('course_id', $course->id)
            ->whereNull('enrollee_id')
            ->first();
        
        if ($masterEval) {
            $masterEval->due_date = $masterEval->created_at
                ? $masterEval->created_at->copy()->addDays(7)
                : null;
            $evaluations = collect([$masterEval]);
        } else {
            $evaluations = collect([]);
        }

        // Feedback stats for implementor view - using new response tables
        $enrolledCount = CourseEnrollee::where('course_id', $course->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->count();

        // Get all completed program evaluations for this course
        $completedEvalIds = ProgramEvaluation::where('course_id', $course->id)
            ->whereNotNull('submitted_at')
            ->pluck('id');
            
        // Get all completed implementer evaluations for this course  
        $completedImpEvalIds = ImplementerEvaluation::where('course_id', $course->id)
            ->whereNotNull('submitted_at')
            ->pluck('id');

        // Get program rating questions and their responses
        $programRatingQuestions = EvaluationQuestion::programRatings()->active()->get();
        $programCommentQuestions = EvaluationQuestion::programComments()->active()->get();
        $implementerRatingQuestions = EvaluationQuestion::implementerRatings()->active()->get();
        $implementerCommentQuestions = EvaluationQuestion::implementerComments()->active()->get();

        // Calculate course feedback stats from rating responses
        $courseRatingResponses = RatingResponse::whereIn('prog_evaluation_id', $completedEvalIds)->get();
        $courseCommentResponses = CommentResponse::whereIn('prog_evaluation_id', $completedEvalIds)->get();
        
        $courseFeedbackStats = [
            'total_responses' => $completedEvalIds->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($completedEvalIds->count() / $enrolledCount) * 100, 1) : 0,
            'questions' => $programRatingQuestions->map(function ($question) use ($courseRatingResponses) {
                $questionResponses = $courseRatingResponses->where('prog_rating_id', $question->id);
                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'average' => $questionResponses->count() > 0 ? round($questionResponses->avg('rating_value'), 1) : null,
                    'count' => $questionResponses->count(),
                    'distribution' => $questionResponses->groupBy('rating_value')->map->count(),
                ];
            }),
        ];

        // Calculate implementor feedback stats from rating responses
        $impRatingResponses = ImplementerRatingResponse::whereIn('imp_eval_id', $completedImpEvalIds)->get();
        $impCommentResponses = ImplementerCommentResponse::whereIn('imp_eval_id', $completedImpEvalIds)->get();
        
        $implementorFeedbackStats = [
            'total_responses' => $completedImpEvalIds->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($completedImpEvalIds->count() / $enrolledCount) * 100, 1) : 0,
            'questions' => $implementerRatingQuestions->map(function ($question) use ($impRatingResponses) {
                $questionResponses = $impRatingResponses->where('imp_rating_id', $question->id);
                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'average' => $questionResponses->count() > 0 ? round($questionResponses->avg('rating_value'), 1) : null,
                    'count' => $questionResponses->count(),
                    'distribution' => $questionResponses->groupBy('rating_value')->map->count(),
                ];
            }),
        ];

        // Gather comments
        $feedbackComments = [
            'course' => $courseCommentResponses->map(function ($response) use ($programCommentQuestions) {
                $question = $programCommentQuestions->firstWhere('id', $response->prog_comment_id);
                return [
                    'question' => $question?->text ?? 'Comment',
                    'comment' => $response->comment,
                    'created_at' => $response->created_at?->format('M d, Y'),
                ];
            })->filter(fn($c) => !empty($c['comment'])),
            'implementor' => $impCommentResponses->map(function ($response) use ($implementerCommentQuestions) {
                $question = $implementerCommentQuestions->firstWhere('id', $response->imp_comment_id);
                return [
                    'question' => $question?->text ?? 'Comment',
                    'comment' => $response->comment,
                    'created_at' => $response->created_at?->format('M d, Y'),
                ];
            })->filter(fn($c) => !empty($c['comment'])),
        ];

        $quiz = Quiz::where('course_id', $course->id)
            ->withCount('results')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch modules
        $modules = Module::where('course_id', $course->id)
            ->orderBy('module_number', 'asc')
            ->with('lessons')
            ->get();

            
$timeline = collect()

    ->merge($modules->map(fn ($m) => [
        'type' => 'module',
        'model' => $m,
        'date' => $m->created_at,
    ]))

    ->merge($assignments->map(fn ($a) => [
        'type' => 'assignment',
        'model' => $a,
        'date' => $a->created_at,
    ]))

    ->merge($quiz->map(fn ($q) => [
        'type' => 'quiz',
        'model' => $q,
        'date' => $q->created_at,
    ]))

    ->merge($evaluations->map(fn ($e) => [
        'type' => 'evaluation',
        'model' => $e,
        'date' => $e->created_at,
    ]))

    ->merge($announcements->map(fn ($n) => [
        'type' => 'announcement',
        'model' => $n,
        'date' => $n->created_at,
    ]))

    ->sortBy('date')   // ✅ oldest → newest
    ->values();

        return view('livewire.implementors.implementor-course-details', [
    'course'        => $course,
    'courseId'      => $course->id,
    'modules'       => $modules,
    'assignments'   => $assignments,
    'evaluations'   => $evaluations,
    'quiz'          => $quiz,
    'announcements' => $announcements,
    'timeline'      => $timeline, // ✅ ADD THIS
    'courseFeedbackStats' => $courseFeedbackStats,
    'implementorFeedbackStats' => $implementorFeedbackStats,
    'feedbackComments' => $feedbackComments,
    'enrolledCount' => $enrolledCount,
]);

    }

    public function destroy(Module $module)
    {
        $module->delete();

    return redirect()->back()->with('success', 'Module deleted successfully.');
}

    public function deleteAssignment(Course $course, Assignment $assignment)
    {
        $implementor = auth()->user();
        if (!$implementor || $implementor->role_id !== 2 || $course->implementer_id !== $implementor->id || $assignment->course_id !== $course->id) {
            abort(403, 'Unauthorized.');
        }

        $assignment->delete();

        return redirect()->back()->with('success', 'Assignment deleted successfully.');
    }

}

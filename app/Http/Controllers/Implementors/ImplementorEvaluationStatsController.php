<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\ProgramEvaluation;
use App\Models\ImplementerEvaluation;
use App\Models\EvaluationQuestion;
use App\Models\RatingResponse;
use App\Models\CommentResponse;
use App\Models\ImplementerRatingResponse;
use App\Models\ImplementerCommentResponse;
use Illuminate\Support\Facades\Auth;

class ImplementorEvaluationStatsController extends Controller
{
    public function show(Course $course)
    {
        $implementor = Auth::user();
        if (!$implementor || $implementor->role_id !== 2 || $course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized. You must be the implementor for this course.');
        }

        $enrolledCount = CourseEnrollee::where('course_id', $course->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->count();

        // Get completed evaluations
        $completedProgramEvals = ProgramEvaluation::where('course_id', $course->id)
            ->where('status', 'inactive')
            ->get();
            
        $completedImpEvals = ImplementerEvaluation::where('course_id', $course->id)
            ->where('status', 'inactive')
            ->get();

        // Get questions
        $programRatingQuestions = EvaluationQuestion::programRatings()->active()->get();
        $programCommentQuestions = EvaluationQuestion::programComments()->active()->get();
        $implementerRatingQuestions = EvaluationQuestion::implementerRatings()->active()->get();
        $implementerCommentQuestions = EvaluationQuestion::implementerComments()->active()->get();

        // Get responses
        $courseRatingResponses = RatingResponse::whereIn('prog_evaluation_id', $completedProgramEvals->pluck('id'))->get();
        $courseCommentResponses = CommentResponse::whereIn('prog_evaluation_id', $completedProgramEvals->pluck('id'))->get();
        $impRatingResponses = ImplementerRatingResponse::whereIn('imp_eval_id', $completedImpEvals->pluck('id'))->get();
        $impCommentResponses = ImplementerCommentResponse::whereIn('imp_eval_id', $completedImpEvals->pluck('id'))->get();

        // Build stats
        $courseFeedbackStats = [
            'total_responses' => $completedProgramEvals->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($completedProgramEvals->count() / $enrolledCount) * 100, 1) : 0,
            'questions' => $programRatingQuestions->map(function ($question) use ($courseRatingResponses) {
                $qResponses = $courseRatingResponses->where('prog_rating_id', $question->id);
                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'average' => $qResponses->count() > 0 ? round($qResponses->avg('rating_value'), 1) : null,
                    'count' => $qResponses->count(),
                    'distribution' => $this->buildDistribution($qResponses, 'rating_value'),
                ];
            }),
        ];

        $implementorFeedbackStats = [
            'total_responses' => $completedImpEvals->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($completedImpEvals->count() / $enrolledCount) * 100, 1) : 0,
            'questions' => $implementerRatingQuestions->map(function ($question) use ($impRatingResponses) {
                $qResponses = $impRatingResponses->where('imp_rating_id', $question->id);
                return [
                    'id' => $question->id,
                    'text' => $question->text,
                    'average' => $qResponses->count() > 0 ? round($qResponses->avg('rating_value'), 1) : null,
                    'count' => $qResponses->count(),
                    'distribution' => $this->buildDistribution($qResponses, 'rating_value'),
                ];
            }),
        ];

        // Build trends
        $trends = [
            'course' => $completedProgramEvals->groupBy(fn($e) => $e->created_at?->format('Y-m-d') ?? 'unknown')->map->count(),
            'implementor' => $completedImpEvals->groupBy(fn($e) => $e->created_at?->format('Y-m-d') ?? 'unknown')->map->count(),
        ];

        // Build comments
        $comments = [
            'course' => $courseCommentResponses->filter(fn($r) => !empty($r->comment))->map(function ($response) use ($programCommentQuestions) {
                $question = $programCommentQuestions->firstWhere('id', $response->prog_comment_id);
                return [
                    'question' => $question?->text ?? 'Comment',
                    'comment' => $response->comment,
                    'created_at' => $response->created_at?->format('M d, Y'),
                ];
            }),
            'implementor' => $impCommentResponses->filter(fn($r) => !empty($r->comment))->map(function ($response) use ($implementerCommentQuestions) {
                $question = $implementerCommentQuestions->firstWhere('id', $response->imp_comment_id);
                return [
                    'question' => $question?->text ?? 'Comment',
                    'comment' => $response->comment,
                    'created_at' => $response->created_at?->format('M d, Y'),
                ];
            }),
        ];

        return view('implementor.evaluation-stats', compact(
            'course',
            'courseFeedbackStats',
            'implementorFeedbackStats',
            'trends',
            'comments',
            'enrolledCount'
        ));
    }

    private function buildDistribution($responses, $field)
    {
        $base = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
        $dist = $responses->groupBy($field)->map->count();
        return $base->merge($dist)->sortKeysDesc();
    }
}


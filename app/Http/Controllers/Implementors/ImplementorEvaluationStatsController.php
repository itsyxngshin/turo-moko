<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
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

        $courseFeedbacks = CourseFeedback::where('course_id', $course->id)->get();
        $implementorFeedbacks = ImplementorFeedback::where('course_id', $course->id)->get();

        $courseFeedbackStats = $this->buildCourseStats($courseFeedbacks, $enrolledCount);
        $implementorFeedbackStats = $this->buildImplementorStats($implementorFeedbacks, $enrolledCount);

        $trends = $this->buildTrends($courseFeedbacks, $implementorFeedbacks);
        $comments = $this->buildComments($courseFeedbacks, $implementorFeedbacks);

        return view('implementor.evaluation-stats', compact(
            'course',
            'courseFeedbackStats',
            'implementorFeedbackStats',
            'trends',
            'comments',
            'enrolledCount'
        ));
    }

    private function buildCourseStats($feedbacks, $enrolledCount)
    {
        return [
            'total_responses' => $feedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($feedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'overall' => $feedbacks->avg('overall_rating'),
                'materials' => $feedbacks->avg('materials_rating'),
                'structure' => $feedbacks->avg('structure_rating'),
                'engagement' => $feedbacks->avg('engagement_rating'),
            ],
            'distribution' => [
                'overall' => $this->distribution($feedbacks, 'overall_rating'),
                'materials' => $this->distribution($feedbacks, 'materials_rating'),
                'structure' => $this->distribution($feedbacks, 'structure_rating'),
                'engagement' => $this->distribution($feedbacks, 'engagement_rating'),
            ],
        ];
    }

    private function buildImplementorStats($feedbacks, $enrolledCount)
    {
        return [
            'total_responses' => $feedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($feedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'teaching_effectiveness' => $feedbacks->avg('teaching_effectiveness_rating'),
                'responsiveness' => $feedbacks->avg('responsiveness_rating'),
                'explanation_clarity' => $feedbacks->avg('explanation_clarity_rating'),
                'recommendation' => $feedbacks->avg('recommendation_rating'),
            ],
            'distribution' => [
                'teaching_effectiveness' => $this->distribution($feedbacks, 'teaching_effectiveness_rating'),
                'responsiveness' => $this->distribution($feedbacks, 'responsiveness_rating'),
                'explanation_clarity' => $this->distribution($feedbacks, 'explanation_clarity_rating'),
                'recommendation' => $this->distribution($feedbacks, 'recommendation_rating'),
            ],
        ];
    }

    private function distribution($collection, $field)
    {
        $base = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        return $collection
            ->groupBy($field)
            ->map
            ->count()
            ->union($base)
            ->sortKeysDesc();
    }

    private function buildTrends($courseFeedbacks, $implementorFeedbacks)
    {
        $byDate = function ($collection) {
            return $collection->groupBy(function ($item) {
                return $item->created_at ? $item->created_at->format('Y-m-d') : 'unknown';
            })->map->count();
        };

        return [
            'course' => $byDate($courseFeedbacks),
            'implementor' => $byDate($implementorFeedbacks),
        ];
    }

    private function buildComments($courseFeedbacks, $implementorFeedbacks)
    {
        return [
            'course' => $courseFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
            'implementor' => $implementorFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
        ];
    }
}


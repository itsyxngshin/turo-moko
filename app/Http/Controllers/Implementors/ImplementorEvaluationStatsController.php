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

        // Get all feedback submissions for this course
        $courseFeedbacks = CourseFeedback::where('course_id', $course->id)->get();
        $implementorFeedbacks = ImplementorFeedback::where('course_id', $course->id)->get();

        // Course Feedback Statistics
        $courseFeedbackStats = [
            'total_responses' => $courseFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($courseFeedbacks->count() / $enrolledCount) * 100, 1) : 0,
            'averages' => [
                'overall' => $courseFeedbacks->avg('overall_rating') ? round($courseFeedbacks->avg('overall_rating'), 1) : 0,
                'materials' => $courseFeedbacks->avg('materials_rating') ? round($courseFeedbacks->avg('materials_rating'), 1) : 0,
                'structure' => $courseFeedbacks->avg('structure_rating') ? round($courseFeedbacks->avg('structure_rating'), 1) : 0,
                'engagement' => $courseFeedbacks->avg('engagement_rating') ? round($courseFeedbacks->avg('engagement_rating'), 1) : 0,
            ],
            'distribution' => [
                'overall' => $this->buildDistribution($courseFeedbacks, 'overall_rating'),
                'materials' => $this->buildDistribution($courseFeedbacks, 'materials_rating'),
                'structure' => $this->buildDistribution($courseFeedbacks, 'structure_rating'),
                'engagement' => $this->buildDistribution($courseFeedbacks, 'engagement_rating'),
            ],
            'questions' => [
                [
                    'id' => 1,
                    'text' => 'Overall course rating',
                    'average' => $courseFeedbacks->avg('overall_rating') ? round($courseFeedbacks->avg('overall_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'overall_rating'),
                ],
                [
                    'id' => 2,
                    'text' => 'Course materials quality',
                    'average' => $courseFeedbacks->avg('materials_rating') ? round($courseFeedbacks->avg('materials_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'materials_rating'),
                ],
                [
                    'id' => 3,
                    'text' => 'Course structure',
                    'average' => $courseFeedbacks->avg('structure_rating') ? round($courseFeedbacks->avg('structure_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'structure_rating'),
                ],
                [
                    'id' => 4,
                    'text' => 'Course engagement',
                    'average' => $courseFeedbacks->avg('engagement_rating') ? round($courseFeedbacks->avg('engagement_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'engagement_rating'),
                ],
            ],
        ];

        // Implementor Feedback Statistics
        $implementorFeedbackStats = [
            'total_responses' => $implementorFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($implementorFeedbacks->count() / $enrolledCount) * 100, 1) : 0,
            'averages' => [
                'teaching_effectiveness' => $implementorFeedbacks->avg('teaching_effectiveness_rating') ? round($implementorFeedbacks->avg('teaching_effectiveness_rating'), 1) : 0,
                'responsiveness' => $implementorFeedbacks->avg('responsiveness_rating') ? round($implementorFeedbacks->avg('responsiveness_rating'), 1) : 0,
                'explanation_clarity' => $implementorFeedbacks->avg('explanation_clarity_rating') ? round($implementorFeedbacks->avg('explanation_clarity_rating'), 1) : 0,
                'recommendation' => $implementorFeedbacks->avg('recommendation_rating') ? round($implementorFeedbacks->avg('recommendation_rating'), 1) : 0,
            ],
            'distribution' => [
                'teaching_effectiveness' => $this->buildDistribution($implementorFeedbacks, 'teaching_effectiveness_rating'),
                'responsiveness' => $this->buildDistribution($implementorFeedbacks, 'responsiveness_rating'),
                'explanation_clarity' => $this->buildDistribution($implementorFeedbacks, 'explanation_clarity_rating'),
                'recommendation' => $this->buildDistribution($implementorFeedbacks, 'recommendation_rating'),
            ],
            'questions' => [
                [
                    'id' => 6,
                    'text' => 'Teaching effectiveness',
                    'average' => $implementorFeedbacks->avg('teaching_effectiveness_rating') ? round($implementorFeedbacks->avg('teaching_effectiveness_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'teaching_effectiveness_rating'),
                ],
                [
                    'id' => 7,
                    'text' => 'Responsiveness to learners',
                    'average' => $implementorFeedbacks->avg('responsiveness_rating') ? round($implementorFeedbacks->avg('responsiveness_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'responsiveness_rating'),
                ],
                [
                    'id' => 8,
                    'text' => 'Explanation clarity',
                    'average' => $implementorFeedbacks->avg('explanation_clarity_rating') ? round($implementorFeedbacks->avg('explanation_clarity_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'explanation_clarity_rating'),
                ],
                [
                    'id' => 9,
                    'text' => 'Would recommend this instructor',
                    'average' => $implementorFeedbacks->avg('recommendation_rating') ? round($implementorFeedbacks->avg('recommendation_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'recommendation_rating'),
                ],
            ],
        ];

        // Build trends
        $trends = [
            'course' => $courseFeedbacks->groupBy(fn($e) => $e->created_at?->format('Y-m-d') ?? 'unknown')->map->count(),
            'implementor' => $implementorFeedbacks->groupBy(fn($e) => $e->created_at?->format('Y-m-d') ?? 'unknown')->map->count(),
        ];

        // Build comments
        $comments = [
            'course' => $courseFeedbacks->filter(fn($r) => !empty($r->comment))->map(function ($feedback) {
                return [
                    'question' => 'General feedback',
                    'comment' => $feedback->comment,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            })->values(),
            'implementor' => $implementorFeedbacks->filter(fn($r) => !empty($r->comment))->map(function ($feedback) {
                return [
                    'question' => 'General feedback',
                    'comment' => $feedback->comment,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            })->values(),
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

    private function buildDistribution($collection, $field)
    {
        $base = collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
        $dist = $collection->groupBy($field)->map->count();
        return $base->merge($dist)->sortKeysDesc();
    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
use Illuminate\Support\Facades\Auth;

class AdminEvaluationStatsController extends Controller
{
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Check if user is admin (role_id = 3)
        if (!$user || (int) $user->role_id !== 3) {
            abort(403, 'Unauthorized. Admin access required.');
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
                'overall' => $courseFeedbacks->avg('achievement_rating') ? round($courseFeedbacks->avg('achievement_rating'), 1) : 0,
                'materials' => $courseFeedbacks->avg('appropriateness_rating') ? round($courseFeedbacks->avg('appropriateness_rating'), 1) : 0,
                'structure' => $courseFeedbacks->avg('participation_rating') ? round($courseFeedbacks->avg('participation_rating'), 1) : 0,
                'engagement' => $courseFeedbacks->avg('time_management_rating') ? round($courseFeedbacks->avg('time_management_rating'), 1) : 0,
            ],
            'distribution' => [
                'overall' => $this->buildDistribution($courseFeedbacks, 'achievement_rating'),
                'materials' => $this->buildDistribution($courseFeedbacks, 'appropriateness_rating'),
                'structure' => $this->buildDistribution($courseFeedbacks, 'participation_rating'),
                'engagement' => $this->buildDistribution($courseFeedbacks, 'time_management_rating'),
            ],
            'questions' => [
                [
                    'id' => 1,
                    'text' => 'How well did the course help you understand the key topics?',
                    'average' => $courseFeedbacks->avg('achievement_rating') ? round($courseFeedbacks->avg('achievement_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'achievement_rating'),
                ],
                [
                    'id' => 2,
                    'text' => 'How appropriate was the course content for your needs?',
                    'average' => $courseFeedbacks->avg('appropriateness_rating') ? round($courseFeedbacks->avg('appropriateness_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'appropriateness_rating'),
                ],
                [
                    'id' => 3,
                    'text' => 'How engaging were the course activities and materials?',
                    'average' => $courseFeedbacks->avg('participation_rating') ? round($courseFeedbacks->avg('participation_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'participation_rating'),
                ],
                [
                    'id' => 4,
                    'text' => 'How appropriate was the time needed to complete the course?',
                    'average' => $courseFeedbacks->avg('time_management_rating') ? round($courseFeedbacks->avg('time_management_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($courseFeedbacks, 'time_management_rating'),
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
                    'id' => 8,
                    'text' => 'How would you rate the implementor\'s teaching effectiveness?',
                    'average' => $implementorFeedbacks->avg('teaching_effectiveness_rating') ? round($implementorFeedbacks->avg('teaching_effectiveness_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'teaching_effectiveness_rating'),
                ],
                [
                    'id' => 9,
                    'text' => 'How responsive was the implementor to questions and concerns?',
                    'average' => $implementorFeedbacks->avg('responsiveness_rating') ? round($implementorFeedbacks->avg('responsiveness_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'responsiveness_rating'),
                ],
                [
                    'id' => 10,
                    'text' => 'How clear were the implementor\'s explanations and instructions?',
                    'average' => $implementorFeedbacks->avg('explanation_clarity_rating') ? round($implementorFeedbacks->avg('explanation_clarity_rating'), 1) : null,
                    'distribution' => $this->buildDistribution($implementorFeedbacks, 'explanation_clarity_rating'),
                ],
                [
                    'id' => 11,
                    'text' => 'How likely are you to take another course from this implementor?',
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

        // Build comments - collect all text feedback fields
        $courseComments = [];
        foreach ($courseFeedbacks as $feedback) {
            if (!empty($feedback->liked_most)) {
                $courseComments[] = [
                    'question' => 'What did you like most?',
                    'comment' => $feedback->liked_most,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            }
            if (!empty($feedback->could_improve)) {
                $courseComments[] = [
                    'question' => 'What could be better?',
                    'comment' => $feedback->could_improve,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            }
            if (!empty($feedback->additional_comments)) {
                $courseComments[] = [
                    'question' => 'Additional suggestions',
                    'comment' => $feedback->additional_comments,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            }
        }
        
        $comments = [
            'course' => collect($courseComments)->values(),
            'implementor' => $implementorFeedbacks->filter(fn($r) => !empty($r->comment))->map(function ($feedback) {
                return [
                    'question' => 'Implementor feedback',
                    'comment' => $feedback->comment,
                    'created_at' => $feedback->created_at?->format('M d, Y'),
                ];
            })->values(),
        ];

        return view('admin.evaluation-stats', compact(
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
        // Initialize distribution with integer keys for all ratings 1-5
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        // Count each rating value, handling both string and integer types
        foreach ($collection as $item) {
            $rating = $item->$field;
            if ($rating !== null) {
                $ratingKey = (int) $rating;
                if ($ratingKey >= 1 && $ratingKey <= 5) {
                    $distribution[$ratingKey]++;
                }
            }
        }
        
        // Return as collection sorted by keys descending (5 to 1)
        return collect($distribution)->sortKeysDesc();
    }
}

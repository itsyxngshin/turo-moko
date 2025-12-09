<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Assignment;
use App\Models\Resource;
use App\Models\User;
use App\Models\ProgramEvaluation;
use App\Models\Quiz;
use App\Models\Announcement;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
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

        $evaluations = ProgramEvaluation::where('course_id', $course->id)
            ->get()
            ->map(function ($evaluation) {
                // Default due date: 7 days after creation
                $evaluation->due_date = $evaluation->created_at
                    ? $evaluation->created_at->copy()->addDays(7)
                    : null;
                return $evaluation;
            });

        // Feedback stats for implementor view
        $enrolledCount = CourseEnrollee::where('course_id', $course->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->count();

        $courseFeedbacks = CourseFeedback::where('course_id', $course->id)->get();
        $implementorFeedbacks = ImplementorFeedback::where('course_id', $course->id)->get();

        $courseFeedbackStats = [
            'total_responses' => $courseFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($courseFeedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'overall' => $courseFeedbacks->avg('overall_rating'),
                'materials' => $courseFeedbacks->avg('materials_rating'),
                'structure' => $courseFeedbacks->avg('structure_rating'),
                'engagement' => $courseFeedbacks->avg('engagement_rating'),
            ],
            'distribution' => [
                'overall' => $courseFeedbacks->groupBy('overall_rating')->map->count(),
                'materials' => $courseFeedbacks->groupBy('materials_rating')->map->count(),
                'structure' => $courseFeedbacks->groupBy('structure_rating')->map->count(),
                'engagement' => $courseFeedbacks->groupBy('engagement_rating')->map->count(),
            ],
        ];

        $implementorFeedbackStats = [
            'total_responses' => $implementorFeedbacks->count(),
            'completion_rate' => $enrolledCount > 0 ? round(($implementorFeedbacks->count() / $enrolledCount) * 100, 1) : null,
            'averages' => [
                'teaching_effectiveness' => $implementorFeedbacks->avg('teaching_effectiveness_rating'),
                'responsiveness' => $implementorFeedbacks->avg('responsiveness_rating'),
                'explanation_clarity' => $implementorFeedbacks->avg('explanation_clarity_rating'),
                'recommendation' => $implementorFeedbacks->avg('recommendation_rating'),
            ],
            'distribution' => [
                'teaching_effectiveness' => $implementorFeedbacks->groupBy('teaching_effectiveness_rating')->map->count(),
                'responsiveness' => $implementorFeedbacks->groupBy('responsiveness_rating')->map->count(),
                'explanation_clarity' => $implementorFeedbacks->groupBy('explanation_clarity_rating')->map->count(),
                'recommendation' => $implementorFeedbacks->groupBy('recommendation_rating')->map->count(),
            ],
        ];

        $feedbackComments = [
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

<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Submission;
use App\Models\Evaluation;
use App\Models\Assignment;
use App\Models\ImplementorFeedback;

class ImplementorDashboardController extends Controller
{public function index()
{
    // Use authenticated user instead of hardcoded ID
    $instructor = auth()->user();

    $courses = $instructor
        ? Course::where('implementer_id', $instructor->id)->get()
        : collect();

    $enrolleesCount = $instructor
        ? Course::where('implementer_id', $instructor->id)
                ->withCount('enrollees')
                ->get()
                ->sum('enrollees_count')
        : 0;

    $submissionsCount = 0;
    if ($instructor) {
        $courseIds = $courses->pluck('id');
        $assignmentIds = Assignment::whereIn('lesson_id', $courseIds)->pluck('id');
        $submissionsCount = Submission::whereIn('assignment_id', $assignmentIds)->count();
    }

    // Calculate overall implementor average rating from implementor_feedbacks
    $overallRating = null;
    if ($instructor) {
        $feedbacks = ImplementorFeedback::where('implementer_id', $instructor->id)->get();
        
        if ($feedbacks->isNotEmpty()) {
            $totalAvg = $feedbacks->map(function($feedback) {
                return ($feedback->teaching_effectiveness_rating + 
                        $feedback->responsiveness_rating + 
                        $feedback->explanation_clarity_rating + 
                        $feedback->recommendation_rating) / 4;
            })->avg();
            
            $overallRating = $totalAvg ? round($totalAvg, 1) : null;
        }
    }

    $courseName = $courses->first()?->course_title ?? '--';
    $coursesCount = $courses->count();

    // 🔹 Get most recently updated course for that instructor
    $recentCourse = $instructor
        ? Course::with('activeCoverPhoto')
            ->where('implementer_id', $instructor->id)
            ->orderBy('updated_at', 'desc')
            ->first()
        : null;

    return view('livewire.implementors.implementor-dashboard', compact(
        'instructor',
        'courses',
        'enrolleesCount',
        'submissionsCount',
        'coursesCount',
        'overallRating',
        'courseName',
        'recentCourse' // ✅ pass it to Blade
    ));
}


}

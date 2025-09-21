<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Submission;
use App\Models\Evaluation;
use App\Models\Assignment;

class ImplementorDashboardController extends Controller
{public function index()
{
    $instructor = User::where('id', 2)->where('role_id', 2)->first();

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

    $courseName = $courses->first()?->name ?? '--';

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
        'courseName',
        'recentCourse' // ✅ pass it to Blade
    ));
}


}

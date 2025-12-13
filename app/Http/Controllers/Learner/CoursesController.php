<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // current learner ID

        // Active & visible courses: learner is enrolled AND course is active & visible
        $activeCourses = Course::where('status', 'Active')
            ->where('visibility', 'Visible')
            ->whereHas('enrollees', function ($q) use ($userId) {
                $q->where('course_enrollees.enrollee_id', $userId)
                  ->where('course_enrollees.status', 'active');
            })
            ->get();

        // Completed courses: learner is enrolled AND status = completed
        $completedCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId)
              ->where('course_enrollees.status', 'completed');
        })
        ->where('status', 'Active')
        ->where('visibility', 'Visible')
        ->get();

        // Counts
        $activeCoursesCount = $activeCourses->count();
        $completedCoursesCount = $completedCourses->count();

        // Pending assignments in enrolled courses
        $enrolledCourseIds = CourseEnrollee::where('enrollee_id', $userId)->pluck('course_id');

        $pendingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', 'Pending')
            ->count();

        // Featured course: last enrolled OR global latest active & visible course
        $featuredCourse = $activeCourses->sortByDesc('pivot.enrollment_date')->first();
        $globalLatestCourse = Course::where('status', 'Active')
            ->where('visibility', 'Visible')
            ->latest('updated_at')
            ->first();

        // Only show a featured course if either exists
        $featuredCourseToShow = $featuredCourse ?? $globalLatestCourse ?? null;

        return view('learner.courses', [
            'activeCourses'         => $activeCourses,
            'activeCoursesCount'    => $activeCoursesCount,
            'completedCoursesCount' => $completedCoursesCount,
            'featuredCourse'        => $featuredCourseToShow,
            'pendingActivities'     => $pendingAssignments,
        ]);
    }

    public function show(Course $course)
    {
        return view('learner.course', compact('course'));
    }

    public function completed()
    {
        $userId = Auth::id();

        $completedCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId)
              ->where('course_enrollees.status', 'completed');
        })
        ->where('status', 'Active')
        ->where('visibility', 'Visible')
        ->get();

        return view('learner.completed', [
            'completedCourses'       => $completedCourses,
            'completedCoursesCount'  => $completedCourses->count(),
        ]);
    }
}

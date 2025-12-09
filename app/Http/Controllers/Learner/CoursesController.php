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

        // Active courses: learner is enrolled AND status = active
        $activeCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId)
              ->where('course_enrollees.status', 'active');
        })->get();

        // Completed courses: learner is enrolled AND status = completed
        $completedCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId)
              ->where('course_enrollees.status', 'completed');
        })->get();

        // Counts
        $activeCoursesCount = $activeCourses->count();
        $completedCoursesCount = $completedCourses->count();

        $enrolledCourseIds = CourseEnrollee::where('enrollee_id', $userId)
        ->pluck('course_id');
       // Count pending assignments in those courses
$pendingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
    ->where('status', 'Pending')
    ->count();

        // INSERT FOR EVAL

        return view('learner.courses', [
            'activeCourses'         => $activeCourses,
            'activeCoursesCount'    => $activeCoursesCount,
            'completedCoursesCount' => $completedCoursesCount,
            'featuredCourse'        => $activeCourses->first(),
            'pendingActivities'     => $pendingAssignments,
          //  'pendingEvaluations'    => $pendingEvaluations,
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
        })->get();

        return view('learner.completed', [
            'completedCourses'       => $completedCourses,
            'completedCoursesCount'  => $completedCourses->count(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;

class CoursesController extends Controller
{
    public function index()
    {
        $activeCourses = Course::where('status', 'Active')->get();
        $completedCourses = Course::where('status', 'Completed')->get();

        $activeCoursesCount = $activeCourses->count();
        $completedCoursesCount = $completedCourses->count();

        // Count pending assignments
        $pendingAssignments = Assignment::where('status', 'Pending')->count();

        // Count assignments that need evaluation (if applicable)
        $pendingEvaluations = Assignment::where('status', 'Evaluation Pending')->count();


        return view('learner.courses', [
            'activeCourses' => $activeCourses,
            'activeCoursesCount' => $activeCoursesCount,
            'completedCoursesCount' => $completedCoursesCount,
            'featuredCourse' => $activeCourses->first(),
            'pendingActivities' => $pendingAssignments, 
            'pendingEvaluations' => $pendingEvaluations,
        ]);
    }

    public function show(Course $course)
    {
        return view('learner.course', compact('course'));
    }

    public function completed()
    {
        $completedCourses = Course::where('status', 'Completed')->get();

        return view('learner.completed', [
            'completedCourses' => $completedCourses,
            'completedCoursesCount' => $completedCourses->count(),
        ]);
    }
}

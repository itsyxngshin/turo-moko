<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseEnrollee;

class ClassesController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // ✅ Active courses: learner is enrolled + course not completed
        $activeCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('enrollee_id', $userId)
              ->where('status', 'active'); // only active enrolments
        })->get();

        // ✅ Completed courses: learner is enrolled + course completed
        $completedCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('enrollee_id', $userId)
              ->where('status', 'completed'); // only completed enrolments
        })->get();

        // Optional placeholders
        $pendingActivities = 5;    // replace with real logic if needed
        $pendingEvaluations = 1;   // replace with real logic if needed

        $featuredCourse = $activeCourses->first();

        return view('learner.classes', [
            'activeCourses' => $activeCourses,
            'completedCourses' => $completedCourses,
            'activeCoursesCount' => $activeCourses->count(),
            'completedCoursesCount' => $completedCourses->count(),
            'pendingActivities' => $pendingActivities,
            'pendingEvaluations' => $pendingEvaluations,
            'featuredCourse' => $featuredCourse,
        ]);
    }
}

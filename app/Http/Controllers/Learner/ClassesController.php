<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class ClassesController extends Controller
{
    public function index()
    {
        // Example counts
        $activeCourses = Course::where('status', 'Active')->count();
        $completedCourses = Course::where('status', 'Closed')->count();
        $pendingActivities = 5; // Replace with your logic
        $pendingEvaluations = 1; // Replace with your logic

        // Featured course (first active course)
        $featuredCourse = Course::where('status', 'Active')->first();

        // Fetch all courses for the dashboard
        $courses = Course::where('status', 'Active')->get();

        return view('learner.classes', compact(
            'activeCourses',
            'completedCourses',
            'pendingActivities',
            'pendingEvaluations',
            'featuredCourse',
            'courses'
        ));
    }
}
 
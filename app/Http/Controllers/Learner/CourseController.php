<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
{
    // All courses
    $courses = Course::orderBy('title')->get();

    // Counts for top stats
    $activeCourses = Course::where('progress', '<', 100)->count();
    $completedCourses = Course::where('progress', 100)->count();

    // Temporary placeholders until you have real logic
    $pendingActivities = 5; // e.g., later you can link to Activity model
    $pendingEvaluations = 2; // e.g., later link to Evaluation model

    // Pick a featured course (first one for now)
    $featuredCourse = Course::first();

    return view('learner.classes', compact(
        'courses',
        'activeCourses',
        'completedCourses',
        'pendingActivities',
        'pendingEvaluations',
        'featuredCourse'
    ));
}

}

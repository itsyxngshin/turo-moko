<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Featured course: the most recently updated course
        $featuredCourse = Course::orderByDesc('updated_at')->first();

        // Recent course: only the most recently updated course
        $recentCourses = Course::orderByDesc('updated_at')->take(1)->get();

        // Suggested courses (exclude recent courses if needed)
        $suggestedCourses = Course::where('status', 'Active')
                                  ->where('visibility', 'Visible')
                                  ->take(4)
                                  ->get();

        return view('learner.dashboard', [
            'featuredCourse' => $featuredCourse,
            'recentCourses' => $recentCourses,
            'suggestedCourses' => $suggestedCourses,
        ]);
    }
}

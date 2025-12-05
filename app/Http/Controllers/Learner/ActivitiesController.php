<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActivitiesController extends Controller
{
    public function index()
    {
        // Example data
        $activities = [
            (object)[
                'title' => 'Math Assignment 1',
                'description' => 'Solve problems 1-10',
                'type' => 'assignment',
                'course_name' => 'Algebra',
                'due_date' => now()->addDays(2),
            ],
            (object)[
                'title' => 'Science Quiz',
                'description' => 'Chapters 1-3',
                'type' => 'quiz',
                'course_name' => 'Physics',
                'due_date' => now()->addDays(5),
            ],
        ];

        return view('learner.activities', compact('activities'));
    }
}

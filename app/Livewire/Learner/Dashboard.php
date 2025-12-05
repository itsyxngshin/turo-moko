<?php

namespace App\Http\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\User;

class Dashboard extends Component
{
    
      public function index()
    {
        $enrolleesCount = User::where('role', 'student')->count();
        $implementorsCount = User::where('role', 'mentor')->count();
        $coursesCount = Course::count();

        $activeStudents = $enrolleesCount > 0 ? round(($enrolleesCount / User::count()) * 100) : 0;
        $activeMentors = $implementorsCount > 0 ? round(($implementorsCount / User::count()) * 100) : 0;

        $latestCourses = Course::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'enrolleesCount',
            'implementorsCount',
            'coursesCount',
            'activeStudents',
            'activeMentors',
            'latestCourses'
        ));
    }
}
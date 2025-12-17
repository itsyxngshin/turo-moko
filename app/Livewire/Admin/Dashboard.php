<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseEnrollee;

#[Layout('layouts.layout')] 
class Dashboard extends Component
{
    public function render()
    {
        // 1. Basic Counts
        $enrolleesCount = User::where('role_id', 1)->count(); 
        $implementorsCount = User::where('role_id', 2)->count(); 
        
        // Count ONLY non-deleted courses
        $coursesCount = Course::where('status', '!=', 'deleted')->count();

        // 2. "Active Students" Percentage
        $activeLearnersCount = CourseEnrollee::where('status', 'Active')
            ->distinct('enrollee_id')
            ->count('enrollee_id');
            
        $activeStudents = $enrolleesCount > 0 
            ? round(($activeLearnersCount / $enrolleesCount) * 100) 
            : 0;

        // 3. "Active Mentors" Percentage
        // Ensure we only look at active courses that are NOT deleted
        $activeImplementorsCount = Course::where('status', 'active') 
            ->distinct('implementer_id')
            ->count('implementer_id');

        $activeMentors = $implementorsCount > 0
            ? round(($activeImplementorsCount / $implementorsCount) * 100)
            : 0;

        // 4. Latest Courses List
        $latestCourses = Course::with('activeCoverPhoto')
            ->where('status', '!=', 'deleted') // ✅ FILTER: Hide deleted courses
            ->latest()
            ->take(4)
            ->get();

        return view('livewire.admin.dashboard', [
            'enrolleesCount' => $enrolleesCount,
            'implementorsCount' => $implementorsCount,
            'coursesCount' => $coursesCount,
            'activeStudents' => $activeStudents,
            'activeMentors' => $activeMentors,
            'latestCourses' => $latestCourses,
        ]);
    }
}
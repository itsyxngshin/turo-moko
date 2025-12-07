<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;

class Courses extends Component
{
    public $activeCourses;
    public $completedCourses;
    public $pendingActivities;
    public $pendingEvaluations;
    public $featuredCourse;
    public $courses;

    public function mount()
    {
        // Active & completed courses
        $this->activeCourses = Course::where('status', 'active')->count();
        $this->completedCourses = Course::where('status', 'completed')->count();

        // Placeholder values for now (you can replace with actual relations/logic)
        $this->pendingActivities = 3;
        $this->pendingEvaluations = 2;

        // Featured course (pick latest active one)
        $this->featuredCourse = Course::where('status', 'active')->latest()->first();

        // List of active courses
        $this->courses = Course::where('status', 'active')->get();
    }

    public function render()
    {
        return view('livewire.learner.courses');
    }
}

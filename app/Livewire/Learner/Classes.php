<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;

class Classes extends Component
{
    // Counts (used in stats cards)
    public $activeCourses;          
    public $activeCoursesCount;     
    public $completedCourses;       
    public $completedCoursesCount;  

    // Data for lists / banner
    public $courses;         
    public $featuredCourse;  

    // placeholders / stats
    public $pendingActivities;
    public $pendingEvaluations;

    public function mount()
    {
        // Counts
        $this->activeCourses = Course::where('status', 'Active')->count();
        $this->activeCoursesCount = $this->activeCourses;

        $this->completedCourses = Course::where('status', 'Completed')->count();
        $this->completedCoursesCount = $this->completedCourses;

        // Pending assignments & evaluations (using Assignment model)
        if (class_exists(Assignment::class)) {
            $this->pendingActivities = Assignment::where('status', 'Pending')->count();
            $this->pendingEvaluations = Assignment::where('status', 'Evaluation Pending')->count();
        } else {
            $this->pendingActivities = 0;
            $this->pendingEvaluations = 0;
        }

        // Featured course and list of active courses
        $this->featuredCourse = Course::where('status', 'Active')->latest()->first();
        $this->courses = Course::where('status', 'Active')->get();
    }

    public function render()
    {
        return view('livewire.learner.classes');
    }
}

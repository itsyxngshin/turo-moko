<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Classes extends Component
{
    public $activeCourses;
    public $completedCourses;
    public $pendingActivities;
    public $pendingEvaluations;
    public $featuredCourse;
    public $courses;

    public function mount()
    {
        // For now, you can hardcode sample values
        $this->activeCourses = 3;
        $this->completedCourses = 2;
        $this->pendingActivities = 5;
        $this->pendingEvaluations = 1;

        $this->featuredCourse = (object)[
            'subject' => 'Math',
            'name' => 'Algebra Basics',
        ];

        $this->courses = [
            (object)[
                'title' => 'Intro to Algebra',
                'description' => 'Learn the basics of algebra and problem-solving.',
                'progress' => 40,
                'semester' => '1st Sem SY 2024-2025',
                'image' => '/images/course1.jpg'
            ],
            (object)[
                'title' => 'Geometry',
                'description' => 'Explore the world of shapes and measurements.',
                'progress' => 70,
                'semester' => '1st Sem SY 2024-2025',
                'image' => '/images/course2.jpg'
            ],
        ];
    }

    public function render()
    {
        return view('livewire.learner.classes');
    }
}

<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Profile extends Component
{
    public $studentName = "Student Name";
    public $description = "Any description here";
    public $email = "student.name@example.bicol-u.edu.ph";

    public $activeCourses = 5;
    public $archivedCourses = 5;

    public $courses = [
        [
            'semester' => '1st Sem SY 2024-2025',
            'name' => 'Course Name',
            'description' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit...',
            'image' => '/images/course5.jpg',
            'progress' => 50,
        ],
        [
            'semester' => '1st Sem SY 2024-2025',
            'name' => 'Another Course',
            'description' => 'Another description here...',
            'image' => '/images/cover.jpg',
            'progress' => 50,
        ],
    ];

    public function render()
    {
        return view('livewire.learner.profile');
    }
}

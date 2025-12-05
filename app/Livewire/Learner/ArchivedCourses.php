<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class ArchivedCourses extends Component
{
    public $archivedCourses = [];

    public function mount()
    {
        // Temporary test data (you can replace this later with DB data)
        $this->archivedCourses = [
            [
                'name' => 'Web Development 101',
                'description' => 'Learn HTML, CSS, and JavaScript basics.',
                'semester' => '1st Semester',
                'image' => 'https://img.icons8.com/color/96/000000/html-5.png',
            ],
            [
                'name' => 'Database Systems',
                'description' => 'Understand SQL, normalization, and relational databases.',
                'semester' => '2nd Semester',
                'image' => 'https://img.icons8.com/color/96/000000/database.png',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.learner.archived-courses')
            ->layout('layouts.layout2'); // 👈 use your existing layout
    }
}

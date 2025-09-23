<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Enrolled extends Component
{
    public $enrolledCourses;

    public function mount()
    {
        // Fetch enrolled courses (adjust relationship/model as needed)
        $this->enrolledCourses = auth()->user()->enrolledCourses ?? [];
    }

    public function render()
    {
        return view('livewire.learner.enrolled');
    }
}
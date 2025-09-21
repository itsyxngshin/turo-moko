<?php

namespace App\Http\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;

class Dashboard extends Component
{
    public $courses;

    public function mount()
    {
        // Fetch only visible and active courses
        $this->courses = Course::where('status', 'Active')
            ->where('visibility', 'Visible')
            ->latest()
            ->take(4)
            ->get();
    }

    public function render()
    {
        return view('livewire.learner.dashboard');
    }
}

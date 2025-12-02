<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Course;

class AllCourses extends Component
{
    public $courses;
    public $instructor;

    public function mount()
    {
        $this->instructor = auth()->user(); // or pass id param
        $this->courses = Course::with(['activeCoverPhoto','category'])->latest()->get();
    }

    public function render()
    {
         return view('livewire.implementors.allcourses', [
        'courses' => Course::all(),
    ]);
    
}
}

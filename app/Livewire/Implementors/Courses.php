<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Course;

class CourseInformation extends Component
{
    public $course;

    public function mount($id)
    {
        // Fetch the selected course based on ID
        $this->course = Course::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.implementors.course-information', [
            'course' => $this->course,
        ]);
    }
}



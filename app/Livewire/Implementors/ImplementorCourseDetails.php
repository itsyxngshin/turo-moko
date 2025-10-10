<?php

namespace App\Livewire\Implementors;

use Livewire\Component;

class ImplementorCourseDetails extends Component
{
    public $courseId;

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }
    
    public function render()
    {
        return view('livewire.implementors.implementor-course-details');
    }
}

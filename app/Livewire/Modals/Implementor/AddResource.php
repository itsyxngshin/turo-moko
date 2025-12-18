<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\Course;

class AddResource extends Component
{
   public $courseId;
   public $course;

public function mount($courseId)
{
    $this->courseId = $courseId;
    $this->course = Course::find($courseId);
}



    public function render()
    {
    return view('livewire.modals.implementor.add-resource');
    }
}

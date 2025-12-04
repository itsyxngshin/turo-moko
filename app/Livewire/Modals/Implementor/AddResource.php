<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\Course;

class AddResource extends Component
{
   public $courseId;

public function mount($courseId)
{
    $this->courseId = $courseId;
}



    public function render()
    {
return view('livewire.modals.implementor.add-resource');
    }
}

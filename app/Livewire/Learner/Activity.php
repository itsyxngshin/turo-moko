<?php

namespace App\Http\Livewire\Learner;

use Livewire\Component;
use App\Models\Assignment;

class Activity extends Component
{
     public $assignment;

    public function mount()
    {
        $id = request()->route('id');
        $this->assignment = Assignment::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.learner.assignment');
    }
}

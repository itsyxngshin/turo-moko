<?php

namespace App\Livewire\Learner;

use Livewire\Component;


class Assignment extends Component
{
     public $assignment;

    public function mount($id)
    {
        // Load assignment by ID
        $this->assignment = Assignment::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.learner.assignment');
    }
}
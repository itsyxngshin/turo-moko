<?php

namespace App\Http\Livewire\Learner;

use App\Models\Assignment;
use Livewire\Component;


class Activity extends Component
{
    public $activity;

    public function mount($id)
    {
        // Fetch activity from database using ID
        $this->activity = Assignment::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.learner.activity');
    }
}

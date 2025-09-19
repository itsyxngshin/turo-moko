<?php

namespace App\Http\Livewire\Learner;


use Livewire\Component;


class Activity extends Component
{
    public $activity;

    public function mount($id)
    {
        // Fetch activity from database using ID
        $this->activity = Activity::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.learner.activity');
    }
}

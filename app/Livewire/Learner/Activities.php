<?php

namespace App\Http\Livewire\Learner;

use Livewire\Component;

class Activities extends Component
{
    public $activities;

    public function mount()
    {
        $this->activities = [
            (object)['name' => 'Activity 1', 'status' => 'Pending'],
            (object)['name' => 'Activity 2', 'status' => 'Completed'],
        ];
    }

    public function render()
    {
        return view('livewire.learner.activities');
    }
}

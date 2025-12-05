<?php

namespace App\Http\Livewire\Learner;
use App\Models\Activity;

use Livewire\Component;

class Activities extends Component
{
    public $activities;

    public function mount()
    {
        $this->activities = Activities::all(); // Or filter as needed
        $this->pendingActivities = Activities::where('status', 'Open')
                                        ->where('visibility', 'Active')
                                        ->count();
    }

    public function render()
    {
        return view('livewire.learner.activities');
    }
}

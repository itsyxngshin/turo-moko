<?php

namespace App\Http\Livewire\Learner;
use App\Models\Activity;

use Livewire\Component;

class Activities extends Component
{
    public $activities;

    public function mount()
    {
        $this->activities = Activity::all(); // Or filter as needed
        $this->pendingActivities = Activity::where('status', 'Open')
                                        ->where('visibility', 'Active')
                                        ->count();
    }

    public function render()
    {
        return view('livewire.learner.activities');
    }
}

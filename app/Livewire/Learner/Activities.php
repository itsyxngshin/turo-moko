<?php

namespace App\Http\Livewire\Learner;

use Livewire\Component;
use App\Models\Assignment; // adjust if your model name differs

class Activities extends Component
{
    public $activities;

    public function mount()
    {
        // Fetch only pending activities (adjust query as needed)
        $this->activities = Assignment::where('status', 'pending')->get();
    }

    public function render()
    {
        return view('livewire.learner.activities');
    }
}

<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Evaluation;

class EvaluationStatus extends Component
{
    public $pendingEvaluations;
    public $completedEvaluations;

    public function mount()
    {
        // Get full lists (not counts)
        $this->pendingEvaluations = Evaluation::where('status', 'pending')->get();
        $this->completedEvaluations = Evaluation::where('status', 'completed')->get();
    }

    public function render()
    {
        return view('livewire.learner.evaluation-status');
    }
}

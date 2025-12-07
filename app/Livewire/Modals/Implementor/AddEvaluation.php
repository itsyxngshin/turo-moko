<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\ProgramEvaluation;
use App\Models\ImplementerEvaluation;
use Illuminate\Support\Facades\Auth;

class AddEvaluation extends Component
{
    public $courseId;

    public function mount($courseId): void
    {
        $this->courseId = $courseId;
    }

    public function save(): void
    {
        $implementerId = Auth::id();
        if (!$implementerId) {
            session()->flash('error', 'You must be logged in to add an evaluation.');
            return;
        }

        ProgramEvaluation::create([
            'course_id'   => $this->courseId,
            'enrollee_id' => null,
            'description' => 'Course Evaluation',
            'status'      => 'active',
        ]);

        ImplementerEvaluation::create([
            'course_id'      => $this->courseId,
            'implementer_id' => $implementerId,
            'enrollee_id'    => null,
            'description'    => 'Implementor Evaluation',
            'status'         => 'active',
        ]);

        // Close modal on frontend
        $this->dispatch('evaluation-added');
        session()->flash('success', 'Evaluation item added to this course.');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-evaluation');
    }
}


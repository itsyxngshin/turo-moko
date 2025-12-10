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

        // Check if evaluation already exists for this course
        $existingProgramEval = ProgramEvaluation::where('course_id', $this->courseId)
            ->whereNull('enrollee_id')
            ->first();

        if ($existingProgramEval) {
            $this->dispatch('evaluation-modal-close');
            $this->dispatch('swal:evaluation-exists', [
                'title' => 'Already Exists',
                'text'  => 'An evaluation item already exists for this course.',
                'icon'  => 'info',
            ]);
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

        // Close modal and show success message
        $this->dispatch('evaluation-modal-close');
        
        $this->dispatch('swal:evaluation-added', [
            'title' => 'Success!',
            'text'  => 'Evaluation item added successfully!',
        ]);
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-evaluation');
    }
}


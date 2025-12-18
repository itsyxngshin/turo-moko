<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use App\Models\ProgramEvaluation;
use App\Models\ImplementerEvaluation;
use App\Models\User;
use Illuminate\Support\Facades\Notification; 
use App\Notifications\GeneralNotification;
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

        // Check if evaluation already exists
        $programEvaluationExists = ProgramEvaluation::where('course_id', $this->courseId)->exists();
        $implementerEvaluationExists = ImplementerEvaluation::where('course_id', $this->courseId)
            ->where('implementer_id', $implementerId)
            ->exists();

        if ($programEvaluationExists && $implementerEvaluationExists) {
            $this->dispatch('evaluation-modal-close');
            $this->dispatch('swal:evaluation-exists', [
                'title' => 'Already Exists',
                'text'  => 'An evaluation has already been added to this course.',
                'icon'  => 'info'
            ]);
            return;
        }

        // Create evaluations if they don't exist
        // Get the next order number across ALL timeline items
        $maxOrder = max(
            \App\Models\Module::where('course_id', $this->courseId)->max('order') ?? 0,
            \App\Models\Assignment::where('course_id', $this->courseId)->max('order') ?? 0,
            \App\Models\Quiz::where('course_id', $this->courseId)->max('order') ?? 0,
            ProgramEvaluation::where('course_id', $this->courseId)->max('order') ?? 0,
            \App\Models\Announcement::where('course_id', $this->courseId)->max('order') ?? 0,
            \App\Models\SectionHeader::where('course_id', $this->courseId)->max('order') ?? 0
        );

        ProgramEvaluation::firstOrCreate([
            'course_id'   => $this->courseId,
            'enrollee_id' => null,
        ], [
            'description' => 'Course Evaluation',
            'status'      => 'active',
            'order'       => $maxOrder + 1,
        ]);

        ImplementerEvaluation::firstOrCreate([
            'course_id'      => $this->courseId,
            'implementer_id' => $implementerId,
            'enrollee_id'    => null,
        ], [
            'description'    => 'Implementor Evaluation',
            'status'         => 'active',
        ]);

        // Fetch active enrollees
        $enrollees = User::whereIn('id', function($query) {
            $query->select('enrollee_id')
                  ->from('course_enrollees')
                  ->where('course_id', $this->courseId)
                  ->where('status', 'Active');
        })->get();

        if ($enrollees->count() > 0) {
            Notification::send($enrollees, new GeneralNotification(
                'Evaluation Available', // Title
                "The evaluation forms for your course are now available. Please complete them.", // Message
                // PLEASE UPDATE THIS ROUTE to your actual learner course view route
                route('learner.course.overview', $this->courseId) 
            ));
        }

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
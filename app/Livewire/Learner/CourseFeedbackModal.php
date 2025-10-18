<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\CourseFeedback;
use Illuminate\Support\Facades\Auth;

class CourseFeedbackModal extends Component
{
    // TODO: This modal should be triggered from the course completion page
    // Example: $this->dispatch('openFeedbackModal', courseId: $courseId);
    // Set $showModal = false by default when integrated
    public $showModal = true; // Currently true for testing only
    public $courseId;
    public $feedbackSubmitted = false;

    protected $listeners = ['openFeedbackModal' => 'openModal'];

    public function openModal($courseId)
    {
        $this->courseId = $courseId;
        $this->showModal = true;
        $this->feedbackSubmitted = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function submitFeedback($ratings, $comment)
    {
        if (!$this->courseId || empty($ratings)) {
            return;
        }

        CourseFeedback::create([
            'course_id' => $this->courseId,
            'learner_id' => Auth::id(),
            'overall_rating' => $ratings[1] ?? null,
            'instructor_rating' => $ratings[2] ?? null,
            'materials_rating' => $ratings[3] ?? null,
            'recommendation_rating' => $ratings[4] ?? null,
            'comment' => $comment ?? null,
        ]);

        // Show success message
        $this->feedbackSubmitted = true;

        // Hide modal after 2 seconds
        $this->dispatchBrowserEvent('feedback-submitted'); 
    }

    public function render()
    {
        return view('livewire.learner.course-feedback-modal');
    }
}
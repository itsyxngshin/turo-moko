<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\CourseFeedback;
use Illuminate\Support\Facades\Auth;

class CourseFeedbackModal extends Component
{
    public $showModal = false;
    public $courseId;

    // Listen for event from the frontend when modal should open
    protected $listeners = ['openFeedbackModal' => 'openModal'];

    public function openModal($courseId)
    {
        $this->courseId = $courseId;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    /**
     * Handles feedback submission from Alpine.js
     */
    public function submitFeedback($ratings, $comment)
    {
        // Basic validation
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

        // Notify frontend and close modal
        $this->dispatch('feedbackSubmitted');
        $this->closeModal();

        session()->flash('message', 'Thank you for your feedback!');
    }

    public function render()
    {
        return view('livewire.learner.course-feedback-modal');
    }
}

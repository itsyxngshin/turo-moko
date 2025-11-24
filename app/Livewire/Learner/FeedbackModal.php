<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
use Illuminate\Support\Facades\Auth;

class FeedbackModal extends Component
{
    public $showModal = false;
    public $courseId;
    public $implementerId;
    public $feedbackSubmitted = false;

    protected $listeners = ['openFeedbackModal' => 'openModal'];

    /**
     * Open the feedback modal with course and implementor data
     * This should be called from wherever the course completion happens
     * 
     * Usage: $this->dispatch('openFeedbackModal', courseId: $courseId, implementerId: $implementerId);
     */
    public function openModal($courseId, $implementerId)
    {
        $this->courseId = $courseId;
        $this->implementerId = $implementerId;
        $this->showModal = true;
        $this->feedbackSubmitted = false;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['courseId', 'implementerId', 'feedbackSubmitted']);
    }

    public function submitFeedback($courseRatings, $implementorRatings, $courseComment, $implementorComment)
    {
        // Validate required data
        if (!$this->courseId || !$this->implementerId || empty($courseRatings) || empty($implementorRatings)) {
            session()->flash('error', 'Missing required feedback data.');
            return;
        }

        // Get authenticated user ID
        // If auth is not set up yet, this will fail gracefully
        $learnerId = Auth::id();
        
        if (!$learnerId) {
            session()->flash('error', 'You must be logged in to submit feedback.');
            return;
        }

        try {
            // Save course feedback
            CourseFeedback::create([
                'course_id' => $this->courseId,
                'learner_id' => $learnerId,
                'overall_rating' => $courseRatings[1] ?? null,
                'materials_rating' => $courseRatings[2] ?? null,
                'structure_rating' => $courseRatings[3] ?? null,
                'engagement_rating' => $courseRatings[4] ?? null,
                'comment' => $courseComment ?? null,
            ]);

            // Save implementor feedback
            ImplementorFeedback::create([
                'course_id' => $this->courseId,
                'learner_id' => $learnerId,
                'implementer_id' => $this->implementerId,
                'teaching_effectiveness_rating' => $implementorRatings[6] ?? null,
                'responsiveness_rating' => $implementorRatings[7] ?? null,
                'explanation_clarity_rating' => $implementorRatings[8] ?? null,
                'recommendation_rating' => $implementorRatings[9] ?? null,
                'comment' => $implementorComment ?? null,
            ]);

            $this->feedbackSubmitted = true;
            $this->dispatch('feedback-submitted');
            session()->flash('success', 'Feedback submitted successfully!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit feedback. Please try again.');
            \Log::error('Feedback submission error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.learner.feedback-modal');
    }
}
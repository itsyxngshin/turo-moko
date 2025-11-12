<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;
use Illuminate\Support\Facades\Auth;

class FeedbackModal extends Component
{
    public $showModal = true; // TODO: Set to false when integrated
    public $courseId = 5;
    public $implementerId = 1;
    public $feedbackSubmitted = false;

    protected $listeners = ['openFeedbackModal' => 'openModal'];

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
    }

    public function submitFeedback($courseRatings, $implementorRatings, $courseComment, $implementorComment)
    {
        if (!$this->courseId || !$this->implementerId || empty($courseRatings) || empty($implementorRatings)) {
            return;
        }

        // Save course feedback
        CourseFeedback::create([
            'course_id' => $this->courseId,
            'learner_id' => 1,//Auth::id(),
            'overall_rating' => $courseRatings[1] ?? null,
            'materials_rating' => $courseRatings[2] ?? null,
            'structure_rating' => $courseRatings[3] ?? null,
            'engagement_rating' => $courseRatings[4] ?? null,
            'comment' => $courseComment ?? null,
        ]);

        // Save implementor feedback
        ImplementorFeedback::create([
            'course_id' => $this->courseId,
            'learner_id' => 1,// Auth::id(),
            'implementer_id' => $this->implementerId,
            'teaching_effectiveness_rating' => $implementorRatings[6] ?? null,
            'responsiveness_rating' => $implementorRatings[7] ?? null,
            'explanation_clarity_rating' => $implementorRatings[8] ?? null,
            'recommendation_rating' => $implementorRatings[9] ?? null,
            'comment' => $implementorComment ?? null,
        ]);

        $this->feedbackSubmitted = true;
        $this->dispatch('feedback-submitted'); 
    }

    public function render()
    {
        return view('livewire.learner.feedback-modal');
    }
}
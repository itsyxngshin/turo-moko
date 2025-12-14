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

    public function submitFeedback($courseRatings, $likedMost, $couldBeBetter, $additionalSuggestions, $implementorRatings, $implementorComment)
    {
        // Validate required data
        if (!$this->courseId || !$this->implementerId) {
            session()->flash('error', 'Missing course or implementor information.');
            return;
        }

        // Convert objects to arrays if needed
        $courseRatings = is_object($courseRatings) ? (array) $courseRatings : $courseRatings;
        $implementorRatings = is_object($implementorRatings) ? (array) $implementorRatings : $implementorRatings;

        // Get authenticated user ID
        $learnerId = Auth::id();
        
        if (!$learnerId) {
            session()->flash('error', 'You must be logged in to submit feedback.');
            return;
        }

        try {
            // Extract course ratings - handle both string and numeric keys from JavaScript
            // Cast to integers to ensure proper database storage
            // Q1: How well did the course help you understand key topics? -> achievement_rating
            // Q2: How appropriate was the course content? -> appropriateness_rating
            // Q3: How engaging were activities? -> participation_rating
            // Q4: How appropriate was the time needed? -> time_management_rating
            $achievementRating = (int) ($courseRatings['1'] ?? $courseRatings[1] ?? 0) ?: null;
            $appropriatenessRating = (int) ($courseRatings['2'] ?? $courseRatings[2] ?? 0) ?: null;
            $participationRating = (int) ($courseRatings['3'] ?? $courseRatings[3] ?? 0) ?: null;
            $timeManagementRating = (int) ($courseRatings['4'] ?? $courseRatings[4] ?? 0) ?: null;
            
            $teachingRating = (int) ($implementorRatings['8'] ?? $implementorRatings[8] ?? 0) ?: null;
            $responsivenessRating = (int) ($implementorRatings['9'] ?? $implementorRatings[9] ?? 0) ?: null;
            $clarityRating = (int) ($implementorRatings['10'] ?? $implementorRatings[10] ?? 0) ?: null;
            $recommendationRating = (int) ($implementorRatings['11'] ?? $implementorRatings[11] ?? 0) ?: null;

            // Save course feedback with correct column names
            CourseFeedback::create([
                'course_id' => $this->courseId,
                'learner_id' => $learnerId,
                'achievement_rating' => $achievementRating,
                'appropriateness_rating' => $appropriatenessRating,
                'participation_rating' => $participationRating,
                'time_management_rating' => $timeManagementRating,
                'liked_most' => $likedMost ?: null,
                'could_improve' => $couldBeBetter ?: null,
                'additional_comments' => $additionalSuggestions ?: null,
            ]);

            // Save implementor feedback
            ImplementorFeedback::create([
                'course_id' => $this->courseId,
                'learner_id' => $learnerId,
                'implementer_id' => $this->implementerId,
                'teaching_effectiveness_rating' => $teachingRating,
                'responsiveness_rating' => $responsivenessRating,
                'explanation_clarity_rating' => $clarityRating,
                'recommendation_rating' => $recommendationRating,
                'comment' => $implementorComment ?? null,
            ]);

            $this->feedbackSubmitted = true;
            $this->dispatch('feedback-submitted');
            
            return ['success' => true, 'message' => 'Feedback submitted successfully!'];
            
        } catch (\Exception $e) {
            \Log::error('Feedback submission error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return ['success' => false, 'message' => 'Failed to submit feedback: ' . $e->getMessage()];
        }
    }

    public function render()
    {
        return view('livewire.learner.feedback-modal');
    }
}
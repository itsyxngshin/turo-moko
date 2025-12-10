<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\EvaluationQuestion;
use App\Models\ProgramEvaluation;
use App\Models\ImplementerEvaluation;
use App\Models\RatingResponse;
use App\Models\CommentResponse;
use App\Models\ImplementerRatingResponse;
use App\Models\ImplementerCommentResponse;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class FeedbackModal extends Component
{
    public $showModal = false;
    public $courseId;
    public $implementerId;
    public $feedbackSubmitted = false;
    
    // Dynamic questions
    public $programRatingQuestions = [];
    public $programCommentQuestions = [];
    public $implementerRatingQuestions = [];
    public $implementerCommentQuestions = [];

    protected $listeners = ['openFeedbackModal' => 'openModal'];

    /**
     * Open the feedback modal with course and implementor data
     */
    public function openModal($courseId, $implementerId)
    {
        $this->courseId = $courseId;
        $this->implementerId = $implementerId;
        
        // Check if user has already submitted evaluation
        $enrollee = CourseEnrollee::where('course_id', $courseId)
            ->where('enrollee_id', auth()->id())
            ->first();
        
        if ($enrollee) {
            $hasCompletedProgram = ProgramEvaluation::where('course_id', $courseId)
                ->where('enrollee_id', $enrollee->id)
                ->whereNotNull('submitted_at')
                ->exists();
                
            $hasCompletedImplementer = ImplementerEvaluation::where('implementer_id', $implementerId)
                ->where('enrollee_id', $enrollee->id)
                ->whereNotNull('submitted_at')
                ->exists();
                
            if ($hasCompletedProgram && $hasCompletedImplementer) {
                session()->flash('info', 'You have already submitted your evaluation for this course.');
                return;
            }
        }
        
        $this->showModal = true;
        $this->feedbackSubmitted = false;
        
        // Load active evaluation questions
        $this->programRatingQuestions = EvaluationQuestion::programRatings()->active()->get()->toArray();
        $this->programCommentQuestions = EvaluationQuestion::programComments()->active()->get()->toArray();
        $this->implementerRatingQuestions = EvaluationQuestion::implementerRatings()->active()->get()->toArray();
        $this->implementerCommentQuestions = EvaluationQuestion::implementerComments()->active()->get()->toArray();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['courseId', 'implementerId', 'feedbackSubmitted', 
                     'programRatingQuestions', 'programCommentQuestions', 
                     'implementerRatingQuestions', 'implementerCommentQuestions']);
    }

    public function submitFeedback($responses)
    {
        // Validate required data
        if (!$this->courseId || !$this->implementerId) {
            session()->flash('error', 'Missing required feedback data.');
            return;
        }

        $learnerId = Auth::id();
        
        if (!$learnerId) {
            session()->flash('error', 'You must be logged in to submit feedback.');
            return;
        }

        try {
            \DB::beginTransaction();

            // Get enrollee ID
            $enrollee = \App\Models\CourseEnrollee::where('enrollee_id', $learnerId)
                ->where('course_id', $this->courseId)
                ->first();

            if (!$enrollee) {
                throw new \Exception('You are not enrolled in this course.');
            }

            // Create or get program evaluation record
            $programEval = ProgramEvaluation::firstOrCreate([
                'course_id' => $this->courseId,
                'enrollee_id' => $enrollee->id,
            ], [
                'description' => 'Course Evaluation',
                'status' => 'active',
            ]);

            // Mark as submitted (inactive = completed)
            $programEval->update([
                'status' => 'inactive',
                'submitted_at' => now()
            ]);

            // Create or get implementer evaluation record
            $implementerEval = ImplementerEvaluation::firstOrCreate([
                'course_id' => $this->courseId,
                'implementer_id' => $this->implementerId,
                'enrollee_id' => $enrollee->id,
            ], [
                'description' => 'Implementor Evaluation',
                'status' => 'active',
            ]);

            // Mark as submitted (inactive = completed)
            $implementerEval->update([
                'status' => 'inactive',
                'submitted_at' => now()
            ]);

            // Save responses for each question
            foreach ($responses as $questionId => $response) {
                $question = EvaluationQuestion::find($questionId);
                
                if (!$question) continue;

                if ($question->type === 'program_rating') {
                    RatingResponse::updateOrCreate([
                        'prog_rating_id' => $questionId,
                        'prog_evaluation_id' => $programEval->id,
                        'enrollee_id' => $enrollee->id,
                    ], [
                        'rating_value' => $response['rating'] ?? null,
                    ]);
                } elseif ($question->type === 'program_comment') {
                    CommentResponse::updateOrCreate([
                        'prog_comment_id' => $questionId,
                        'prog_evaluation_id' => $programEval->id,
                        'enrollee_id' => $enrollee->id,
                    ], [
                        'comment' => $response['comment'] ?? null,
                    ]);
                } elseif ($question->type === 'implementer_rating') {
                    ImplementerRatingResponse::updateOrCreate([
                        'imp_rating_id' => $questionId,
                        'imp_eval_id' => $implementerEval->id,
                        'enrollee_id' => $enrollee->id,
                    ], [
                        'rating_value' => $response['rating'] ?? null,
                    ]);
                } elseif ($question->type === 'implementer_comment') {
                    ImplementerCommentResponse::updateOrCreate([
                        'imp_comment_id' => $questionId,
                        'imp_eval_id' => $implementerEval->id,
                        'enrollee_id' => $enrollee->id,
                    ], [
                        'comment' => $response['comment'] ?? null,
                    ]);
                }
            }

            \DB::commit();

            $this->feedbackSubmitted = true;
            $this->dispatch('feedback-submitted');
            
            // Refresh the page after a delay to show updated status
            $this->dispatch('refresh-page-after-delay');
            
        } catch (\Exception $e) {
            \DB::rollBack();
            session()->flash('error', 'Failed to submit feedback: ' . $e->getMessage());
            \Log::error('Feedback submission error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.learner.feedback-modal');
    }
}

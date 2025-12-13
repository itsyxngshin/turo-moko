<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\CourseFeedback;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class EvaluationStatus extends Component
{
    public $pendingEvaluations = [];
    public $completedEvaluations = [];

    public function mount()
    {
        $user = Auth::user();
        
        // Get enrolled courses where implementor has created an evaluation item
        $enrolledCourses = $user->enrolledCourses()
            ->where('courses.status', 'active')
            ->wherePivot('status', 'active')
            ->whereHas('evaluations') // Only courses with evaluation items
            ->get();
        
        // Pending: Courses with evaluation items where learner hasn't submitted feedback
        $this->pendingEvaluations = $enrolledCourses->filter(function($course) use ($user) {
            return !CourseFeedback::where('course_id', $course->id)
                ->where('learner_id', $user->id)
                ->exists();
        })->map(function($course) {
            return (object)[
                'id' => $course->id,
                'title' => $course->course_title,
                'course_code' => $course->course_code,
                'description' => 'Course evaluation feedback',
                'due_date' => null, // Evaluations typically don't have due dates
            ];
        });
        
        // Completed: Courses with evaluation items where learner has submitted feedback
        $this->completedEvaluations = $enrolledCourses->filter(function($course) use ($user) {
            return CourseFeedback::where('course_id', $course->id)
                ->where('learner_id', $user->id)
                ->exists();
        })->map(function($course) use ($user) {
            $feedback = CourseFeedback::where('course_id', $course->id)
                ->where('learner_id', $user->id)
                ->first();
                
            return (object)[
                'id' => $course->id,
                'title' => $course->course_title,
                'course_code' => $course->course_code,
                'description' => 'Course evaluation feedback',
                'completed_at' => $feedback->created_at ?? null,
            ];
        });
    }

    public function render()
    {
        return view('livewire.learner.evaluation-status', [
            'pendingEvaluations' => $this->pendingEvaluations,
            'completedEvaluations' => $this->completedEvaluations,
        ])->layout('layouts.learner-layout')->title('Evaluation');
    }
}

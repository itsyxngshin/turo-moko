<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Course;
use App\Models\Module;
use App\Models\Assignment;
use App\Models\Resource;
use App\Models\ProgramEvaluation;
use App\Models\Quiz;
use App\Models\Announcement;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;

class ImplementorCourseDetails extends Component
{
    public $course;
    public $courseId;
    public $modules;
    public $assignments;
    public $evaluations;
    public $quiz;
    public $announcements;
    public $courseFeedbackStats;
    public $implementorFeedbackStats;
    public $feedbackComments;
    public $enrolledCount;

    public function mount($courseCode)
    {
        $this->loadCourseData($courseCode);
    }

    #[On('evaluation-added')]
    #[On('announcement-saved')]
    #[On('module-modal-close')]
    public function refreshCourseData()
    {
        $this->loadCourseData($this->course->course_code);
    }

    private function loadCourseData($courseCode)
    {
        // Get logged-in user
        $implementor = auth()->user();

        // Ensure the user is an implementor
        if (!$implementor || $implementor->role_id != 2) {
            abort(403, 'Unauthorized: Only implementors can access this page.');
        }

        // Fetch course
        $this->course = Course::where('course_code', $courseCode)
            ->firstOrFail();

        // Ensure the implementor OWNS this course
        if ($this->course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized: You do not own this course.');
        }

        $this->courseId = $this->course->id;

        // Fetch related data
        $this->announcements = Announcement::where('course_id', $this->course->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->assignments = Assignment::where('course_id', $this->course->id)
            ->withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->evaluations = ProgramEvaluation::where('course_id', $this->course->id)
            ->get()
            ->map(function ($evaluation) {
                // Default due date: 7 days after creation
                $evaluation->due_date = $evaluation->created_at
                    ? $evaluation->created_at->copy()->addDays(7)
                    : null;
                return $evaluation;
            });

        // Feedback stats for implementor view
        $this->enrolledCount = CourseEnrollee::where('course_id', $this->course->id)
            ->whereIn('status', ['Active', 'Completed'])
            ->count();

        $courseFeedbacks = CourseFeedback::where('course_id', $this->course->id)->get();
        $implementorFeedbacks = ImplementorFeedback::where('course_id', $this->course->id)->get();

        $this->courseFeedbackStats = [
            'total_responses' => $courseFeedbacks->count(),
            'completion_rate' => $this->enrolledCount > 0 ? round(($courseFeedbacks->count() / $this->enrolledCount) * 100, 1) : null,
            'averages' => [
                'overall' => $courseFeedbacks->avg('overall_rating'),
                'materials' => $courseFeedbacks->avg('materials_rating'),
                'structure' => $courseFeedbacks->avg('structure_rating'),
                'engagement' => $courseFeedbacks->avg('engagement_rating'),
            ],
            'distribution' => [
                'overall' => $courseFeedbacks->groupBy('overall_rating')->map->count(),
                'materials' => $courseFeedbacks->groupBy('materials_rating')->map->count(),
                'structure' => $courseFeedbacks->groupBy('structure_rating')->map->count(),
                'engagement' => $courseFeedbacks->groupBy('engagement_rating')->map->count(),
            ],
        ];

        $this->implementorFeedbackStats = [
            'total_responses' => $implementorFeedbacks->count(),
            'completion_rate' => $this->enrolledCount > 0 ? round(($implementorFeedbacks->count() / $this->enrolledCount) * 100, 1) : null,
            'averages' => [
                'teaching_effectiveness' => $implementorFeedbacks->avg('teaching_effectiveness_rating'),
                'responsiveness' => $implementorFeedbacks->avg('responsiveness_rating'),
                'explanation_clarity' => $implementorFeedbacks->avg('explanation_clarity_rating'),
                'recommendation' => $implementorFeedbacks->avg('recommendation_rating'),
            ],
            'distribution' => [
                'teaching_effectiveness' => $implementorFeedbacks->groupBy('teaching_effectiveness_rating')->map->count(),
                'responsiveness' => $implementorFeedbacks->groupBy('responsiveness_rating')->map->count(),
                'explanation_clarity' => $implementorFeedbacks->groupBy('explanation_clarity_rating')->map->count(),
                'recommendation' => $implementorFeedbacks->groupBy('recommendation_rating')->map->count(),
            ],
        ];

        $this->feedbackComments = [
            'course' => $courseFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
            'implementor' => $implementorFeedbacks->whereNotNull('comment')->map(function ($item) {
                return [
                    'comment' => $item->comment,
                    'created_at' => $item->created_at?->format('M d, Y'),
                ];
            }),
        ];

        $this->quiz = Quiz::where('course_id', $this->course->id)
            ->withCount('results')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch modules
        $this->modules = Module::where('course_id', $this->course->id)
            ->orderBy('module_number', 'asc')
            ->with('lessons')
            ->get();
    }
    
    public function render()
    {
        return view('livewire.implementors.implementor-course-details')
            ->extends('layouts.layout')
            ->section('content');
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use App\Models\Module;
use App\Models\CourseFeedback;
use App\Models\ImplementorFeedback;

class CourseModeration extends Component
{
    public $course;
    public $modules;
    public $courseFeedbackStats = [];
public $implementorFeedbackStats = [];



public function mount($id)
{
    $this->course = Course::with(['modules','implementer'])->findOrFail($id);

    $this->refreshModules();
    $this->loadFeedbackStats();
}
private function loadFeedbackStats()
{
    $courseFeedbacks = CourseFeedback::where('course_id', $this->course->id)->get();
    $implementorFeedbacks = ImplementorFeedback::where('course_id', $this->course->id)->get();

    $this->courseFeedbackStats = [
        'averages' => [
            // MUST match Eval Stats controller
            'overall' => round(
                $courseFeedbacks->avg('achievement_rating') ?? 0,
                1
            ),
        ],
    ];

    $this->implementorFeedbackStats = [
        'averages' => [
            'teaching_effectiveness' => round(
                $implementorFeedbacks->avg('teaching_effectiveness_rating') ?? 0,
                1
            ),
        ],
    ];
}


public function getActiveEnrolleesCountProperty()
{
    return $this->course->enrolleeRecords()
                ->where('status', 'active') // or ->where('is_active', 1) depending on your schema
                ->count();
}


    public function approveModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->update([
            'status' => 'approved',
            'visibility' => 'visible',
        ]);

        $this->refreshModules();
    }

    public function rejectModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        $module->update([
            'status' => 'revision_required',
            'visibility' => 'hidden',
        ]);

        $this->refreshModules();
    }

    private function refreshModules()
    {
        $this->modules = $this->course->modules()->orderBy('module_number')->get();
    }

    public function render()
    {
        return view('livewire.admin.course-moderation', [
            'course' => $this->course,
            'modules' => $this->modules
        ])->layout('layouts.layout');
    }
}

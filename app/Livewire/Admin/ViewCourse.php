<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Evaluation;
use App\Models\Announcement;

class ViewCourse extends Component
{
    public $course;
    public $timeline = [];
    public $courseCode;

    public function mount($courseCode)
    {
        $this->courseCode = $courseCode; // Accepts ID or course_code

        $this->course = Course::with([
            'modules.lessons', // hasOne lesson
            'assignments',
            'quizzes',
            'evaluations',
            'announcements.attachments'
        ])
        ->when(is_numeric($courseCode), fn($query) => $query->where('id', $courseCode),
               fn($query) => $query->where('course_code', $courseCode))
        ->firstOrFail();

        $this->buildTimeline();
    }

    private function buildTimeline()
    {
        $timeline = [];

        // Modules and their lessons
        foreach ($this->course->modules as $module) {
            $timeline[] = [
                'type' => 'module',
                'data' => $module,
                'created_at' => $module->created_at
            ];

            if ($module->lessons) {  // hasOne returns a single model or null
                $timeline[] = [
                    'type' => 'lesson',
                    'data' => $module->lessons,
                    'created_at' => $module->lessons->created_at
                ];
            }
        }

        // Assignments (directly linked to course)
        foreach ($this->course->assignments as $assignment) {
            $timeline[] = [
                'type' => 'assignment',
                'data' => $assignment,
                'created_at' => $assignment->created_at
            ];
        }

        // Quizzes
        foreach ($this->course->quizzes as $quiz) {
            $timeline[] = [
                'type' => 'quiz',
                'data' => $quiz,
                'created_at' => $quiz->created_at
            ];
        }

        // Evaluations
        foreach ($this->course->evaluations as $evaluation) {
            $timeline[] = [
                'type' => 'evaluation',
                'data' => $evaluation,
                'created_at' => $evaluation->created_at
            ];
        }

        // Announcements
        foreach ($this->course->announcements as $announcement) {
            $timeline[] = [
                'type' => 'announcement',
                'data' => $announcement,
                'created_at' => $announcement->created_at
            ];
        }

        // Sort timeline by creation date
        $this->timeline = collect($timeline)->sortBy('created_at')->values();
    }

    public function render()
    {
        return view('livewire.admin.view-course')
            ->layout('layouts.layout');
    }
}

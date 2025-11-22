<?php

namespace App\Livewire\Course;

use Livewire\Component;
use App\Models\Course;

class ViewCourse extends Component
{
    public $course;
    public $modules = [];
    public $assignments = [];
    public $evaluations = [];
    public $announcements = [];

    public function mount($courseCode)
    {
        $this->course = Course::where('course_code', $courseCode)
            ->with([
                'activeCoverPhoto',
                'enrollees',
                'modules',
                'assignments',
                'evaluations',
                'announcements',
            ])
            ->firstOrFail();

        // Assign collections
        $this->modules = $this->course->modules;
        $this->assignments = $this->course->assignments;
        $this->evaluations = $this->course->evaluations;
        $this->announcements = $this->course->announcements;
    }

    public function render()
    {
        return view('livewire.admin.view-course');
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;

class ViewCourse extends Component
{
    public $courseCode;    // Accept course_code from URL or Blade
    public $course;
    public $modules;
    public $evaluations;
    public $announcements;

    /**
     * Mount the component with course_code
     */
    public function mount($courseCode)
    {
        $this->courseCode = $courseCode;

        // Fetch course by course_code
        $this->course = Course::with([
            'activeCoverPhoto',
            'enrollees',
            'modules.lessons',          // fetch lessons for each module
            'evaluations',
            'announcements.attachments' // load attachments with announcements
        ])->where('course_code', $this->courseCode)->firstOrFail();

        // Modules are already loaded via eager loading
        $this->modules = $this->course->modules;
        $this->evaluations = $this->course->evaluations;
        $this->announcements = $this->course->announcements;
    }

    public function render()
    {
        return view('livewire.admin.view-course')->layout('layouts.layout');;
    }
}

<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Course;
use App\Models\Module;

class CourseModeration extends Component
{
    public $course;
    public $modules;

    public function mount($id)
    {
        // Load course with its modules and implementer
        $this->course = Course::with(['modules', 'implementer'])->findOrFail($id);

        $this->refreshModules();
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

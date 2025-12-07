<?php

namespace App\Livewire\Admin\Modal;

use Livewire\Component;
use App\Models\Module;

class CourseModerationViewModule extends Component
{
    public $open = false;
    public $moduleId;
    public $module;
    public $lesson;

    public function mount($moduleId)
    {
        $this->moduleId = $moduleId;
        $this->module = Module::with('lessons')->find($moduleId);
        $this->lesson = $this->module->lessons ?? null;
    }

    public function openModal()
    {
        $this->open = true;
        
    }

    public function closeModal()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.admin.modal.course-moderation-view-module');
    }
}

<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Module;
use App\Models\Lesson;

class AddModule extends Component
{
    use WithFileUploads;

    public $courseId;

    public $module_number;
    public $module_title;
    public $title;
    public $content;
    public $attachments;
    public $attachments_removed = false; // Flag for removed file

    protected $listeners = ['refreshModuleList' => '$refresh'];

    public function mount($courseId)
    {
        $this->courseId = $courseId;
    }

    public function save()
    {
        try {
            $this->validate([
                'module_number' => 'required|integer',
                'module_title'  => 'required|string|max:255',
                'title'         => 'required|string|max:255',
                'content'       => 'required|string',
                'attachments'   => 'nullable|file|max:10240',
            ]);

            // Determine file path
            $filePath = null;
            if (!$this->attachments_removed && $this->attachments) {
                $filePath = $this->attachments->store('attachments', 'public');
            }

            $module = Module::create([
                'course_id'     => $this->courseId,
                'module_number' => $this->module_number,
                'module_title'  => $this->module_title,
            ]);

            Lesson::create([
                'module_id'   => $module->id,
                'title'       => $this->title,
                'content'     => $this->content,
                'attachments' => $filePath,
            ]);

            $this->resetForm();

            $this->dispatch('module-modal-close');

            $this->dispatch('swal:module-added', [
                'title' => 'Success!',
                'text'  => 'Module & Lesson added successfully!',
            ]);

        } catch (\Throwable $e) {
            $this->dispatch('swal:module-error', [
                'title' => 'Error!',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset(['module_number', 'module_title', 'title', 'content', 'attachments', 'attachments_removed']);
        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-module');
    }
}

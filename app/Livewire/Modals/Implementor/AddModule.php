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
    public $content;
    public $attachments;               // Current file upload
    public $removeAttachment = false;  // For tracking removal in UI

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
                'content'       => 'nullable|string',
                'attachments'   => 'nullable|file|max:10240',
            ]);

            $filePath = null;
            $originalName = null;

            if ($this->attachments && !$this->removeAttachment) {
                $filePath = $this->attachments->store('attachments', 'public');
                $originalName = $this->attachments->getClientOriginalName();
            }

            // Get the next order number across ALL timeline items
            $maxOrder = max(
                Module::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Assignment::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Quiz::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\ProgramEvaluation::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\Announcement::where('course_id', $this->courseId)->max('order') ?? 0,
                \App\Models\SectionHeader::where('course_id', $this->courseId)->max('order') ?? 0
            );

            // Create the module
            $module = Module::create([
                'course_id'     => $this->courseId,
                'module_number' => $this->module_number,
                'module_title'  => $this->module_title,
                'order'         => $maxOrder + 1,
            ]);

            // Create the lesson/content
            Lesson::create([
                'module_id'                 => $module->id,
                'content'                   => $this->content,
                'attachments'               => $filePath,
                'attachments_original_name' => $originalName,
            ]);

            $this->resetForm();

            $this->dispatch('module-modal-close');

            $this->dispatch('swal:module-added', [
                'title' => 'Success!',
                'text'  => 'Module & Lesson added successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show field-specific errors
            throw $e;
        } catch (\Throwable $e) {
            $this->dispatch('swal:error', [
                'title' => 'Error!',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function resetForm()
    {
        $this->reset([
            'module_number',
            'module_title',
            'content',
            'attachments',
            'removeAttachment'
        ]);

        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-module');
    }
}

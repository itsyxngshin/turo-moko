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
                'content'       => 'required|string',
                 'attachments'   => 'nullable|file|max:10240',
            ]);

            // Determine file path
           $filePath = null;
$originalName = null;

if ($this->attachments) {
    $filePath = $this->attachments->store('attachments', 'public'); 
    $originalName = $this->attachments->getClientOriginalName();
}


            // Create the module
            $module = Module::create([
                'course_id'     => $this->courseId,
                'module_number' => $this->module_number,
                'module_title'  => $this->module_title,
                'status'        => 'pending',    // Set module as pending
                'visibility'    => 'visible',     // Set module hidden for learners
            ]);

            // Create lesson/content for this module
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

        } catch (\Throwable $e) {
             dd($e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['module_number', 'module_title', 'content', 'attachments', 'attachments_removed']);
        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-module');
    }
}

<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EditModule extends Component
{
    use WithFileUploads;

    public $module;
    public $lesson;

    public $module_id;
    public $module_number;
    public $module_title;
    public $content;
    public $attachments;        
    public $existingAttachment; 
    public $existingAttachmentOriginalName;

    public $removeAttachment = false;
    public bool $open = false;

    protected $rules = [
        'module_number' => 'required|integer',
        'module_title'  => 'required|string|max:255',
        'content'       => 'required|string',
        'attachments'   => 'nullable|file|max:10240',
    ];

    public function mount($moduleId)
    {
        $this->module = Module::find($moduleId);

        if (!$this->module) {
            session()->flash('error', 'Module not found.');
            return;
        }

        $this->module_id     = $this->module->id;
        $this->module_number = $this->module->module_number;
        $this->module_title  = $this->module->module_title;

        // Load the first lesson of the module
        $this->lesson = $this->module->lessons()->first();

        if ($this->lesson) {
            $this->content = $this->lesson->content;
            $this->existingAttachment = $this->lesson->attachments;
            $this->existingAttachmentOriginalName = $this->lesson->attachments_original_name;
        } else {
            $this->content = '';
            $this->existingAttachment = null;
            $this->existingAttachmentOriginalName = null;
        }
    }

    public function markAttachmentForRemoval()
    {
        $this->removeAttachment = true;
        $this->existingAttachment = null;
    }

    public function updateModule()
    {
        try {
            $this->validate();

            if (!$this->module || !$this->lesson) {
                throw new \Exception('Module or lesson not found.');
            }

            $newFilePath = $this->lesson->attachments;
            $originalName = $this->lesson->attachments_original_name;

            // Handle new attachment upload
            if ($this->attachments) {
                $storedFile = $this->attachments->store('attachments', 'public');
                $storedOriginalName = $this->attachments->getClientOriginalName();

                // Delete old file AFTER successful storage
                if ($this->lesson->attachments) {
                    Storage::disk('public')->delete($this->lesson->attachments);
                }

                $newFilePath = $storedFile;
                $originalName = $storedOriginalName;
            }
            // Handle removal of attachment
            elseif ($this->removeAttachment && $this->lesson->attachments) {
                Storage::disk('public')->delete($this->lesson->attachments);
                $newFilePath = null;
                $originalName = null;
            }

            // Update lesson
            $this->lesson->update([
                'content' => $this->content,
                'attachments' => $newFilePath,
                'attachments_original_name' => $originalName,
            ]);

            // Update module
            $this->module->update([
                'module_number' => $this->module_number,
                'module_title' => $this->module_title,
            ]);

            // Reset temporary state
            $this->attachments = null;
            $this->removeAttachment = false;
            $this->existingAttachment = $newFilePath;
            $this->existingAttachmentOriginalName = $originalName;

            Log::info('Module updated successfully', [
                'module_id' => $this->module_id,
                'lesson_id' => $this->lesson->id,
                'attachments' => $newFilePath,
                'attachments_original_name' => $originalName,
            ]);

           $this->dispatch('swal:module-added', [
    'title' => 'Success!',
    'text'  => 'Module updated successfully!'
]);

$this->dispatch('module-modal-close');

        } catch (\Exception $e) {
            Log::error('Module update failed', [
                'error' => $e->getMessage(),
            ]);

            // On error
$this->dispatch('swal:module-error', [
    'title' => 'Error!',
    'text'  => 'Something went wrong!'
]);
        }
    }

    public function render()
    {
        return view('livewire.modals.implementor.edit-module');
    }
}

<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GeneralNotification;

class AddModule extends Component
{
    use WithFileUploads;

    public $courseId;

    public $module_number;
    public $module_title;
    public $content;
    public $attachments;
    
    // FIX: Renamed from $removeAttachment to match frontend request
    public $attachments_removed = false; 

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

            // FIX: Updated check to use $this->attachments_removed
            if ($this->attachments && !$this->attachments_removed) {
                $filePath = $this->attachments->store('attachments', 'public');
                $originalName = $this->attachments->getClientOriginalName();
            }

            // Get the next order number
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

            // ==========================================
            // NOTIFY ENROLLEES
            // ==========================================
            
            $enrollees = User::whereIn('id', function($query) {
                $query->select('enrollee_id')
                      ->from('course_enrollees')
                      ->where('course_id', $this->courseId)
                      ->where('status', 'Active');
            })->get();

            if ($enrollees->count() > 0) {
                Notification::send($enrollees, new GeneralNotification(
                    'New Module Added', 
                    "A new module '{$this->module_title}' has been added to your course.", 
                    // Ensure this route is correct for your app
                    route('learner.course.overview', $this->courseId) 
                ));
            }

            $this->resetForm();

            $this->dispatch('module-modal-close');

            $this->dispatch('swal:module-added', [
                'title' => 'Success!',
                'text'  => 'Module & Lesson added successfully!',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
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
            'module_number', 'module_title', 'content',
            'attachments', 'attachments_removed' // FIX: Reset the correct property
        ]);

        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-module');
    }
}
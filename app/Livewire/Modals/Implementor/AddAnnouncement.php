<?php

namespace App\Livewire\Modals\Implementor;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\Course;

class AddAnnouncement extends Component
{
    use WithFileUploads;

    public $courseId;
    public $course;
    public $title;
    public $details;
    public $attachments; // ✅ match property name
    public $userId;
    public $removeAttachment = false;
    public $uploadKey; // force re-render

    protected $rules = [
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'attachments' => 'nullable|file|max:102400', // ✅ match property name
    ];

    public function mount($courseId = null)
    {
        $this->uploadKey = uniqid();
        $this->userId = auth()->check() ? auth()->id() : 4;

        if ($courseId) {
            $this->courseId = $courseId;
        } elseif (request()->route('course')) {
            $courseParam = request()->route('course');
            $this->courseId = is_object($courseParam) ? $courseParam->id : $courseParam;
        }

        $this->course = $this->courseId ? Course::find($this->courseId) : null;
    }

    public function saveAnnouncement()
    {
        $this->validate();

        $announcement = Announcement::create([
            'course_id' => $this->courseId,
            'user_id'   => $this->userId,
            'title'     => $this->title,
            'content'   => $this->details,
        ]);

        if ($this->attachments) { // ✅ use correct property
            if (!Storage::disk('public')->exists('course_attachments')) {
                Storage::disk('public')->makeDirectory('course_attachments');
            }

            $path = $this->attachments->store('course_attachments', 'public');

            AnnouncementAttachment::create([
                'announcement_id' => $announcement->id,
                'file_path'       => $path,
                'original_name'   => $this->attachments->getClientOriginalName(),
            ]);
        }

        // Reset form including removeAttachment
        $this->reset(['title', 'details', 'attachments', 'removeAttachment']);
        $this->uploadKey = uniqid();

        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text'  => 'Announcement created successfully.',
            'icon'  => 'success',
            'button' => 'OK'
        ]);

        $this->dispatch('announcement-saved');
    }

    public function resetForm()
    {
        $this->reset(['title', 'details', 'attachments', 'removeAttachment']);
        $this->uploadKey = uniqid();
        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-announcement');
    }
}

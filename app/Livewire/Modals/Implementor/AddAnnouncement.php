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
    public $attachments = [];
    public $userId;
    public $uploadKey; // 👈 unique key to force input re-render

    protected $rules = [
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'attachments' => 'nullable|array|max:2',
        'attachments.*' => 'nullable|file|max:102400',
    ];

    public function mount($courseId = null)
    {
        $this->uploadKey = uniqid(); // 👈 generate unique key

        $this->userId = auth()->check() ? auth()->id() : 2;

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

        if (!empty($this->attachments)) {
            if (!Storage::disk('public')->exists('course_attachments')) {
                Storage::disk('public')->makeDirectory('course_attachments');
            }

            foreach ($this->attachments as $file) {
                $path = $file->store('course_attachments', 'public');

                AnnouncementAttachment::create([
                    'announcement_id' => $announcement->id,
                    'file_path'       => $path,
                    'original_name'   => $file->getClientOriginalName(),
                ]);
            }
        }

        // ✅ Reset form & regenerate uploadKey to clear preview
        $this->reset(['title', 'details', 'attachments']);
        $this->uploadKey = uniqid();

        // ✅ SweetAlert and modal event
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
        $this->reset(['title', 'details', 'attachments']);
        $this->uploadKey = uniqid(); // 👈 reset file preview too
        $this->dispatch('reset-upload-box');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-announcement');
    }
}

<?php

namespace App\Livewire\Modals\Implementor;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\Course;

class EditAnnouncement extends Component
{
    use WithFileUploads;

    public $announcementId;
    public $courseId;
    public $course;
    public $title;
    public $details;
    public $attachments = [];
    public $existingAttachment = null; // full file info (path + name)
    public $userId;
    public $uploadKey;

    protected $rules = [
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'attachments' => 'nullable|array|max:2',
        'attachments.*' => 'nullable|file|max:102400',
    ];

    protected $listeners = ['refresh-announcement-modal' => '$refresh'];

    public function mount($announcementId = null)
    {
        $this->uploadKey = uniqid();
        $this->userId = auth()->check() ? auth()->id() : 2;

        if ($announcementId) {
            $this->loadAnnouncement($announcementId);
        }
    }

    #[\Livewire\Attributes\On('loadAnnouncement')]
    public function loadAnnouncement($announcementId)
    {
        $this->announcementId = $announcementId;

        $announcement = Announcement::with('attachments')->find($announcementId);

        if ($announcement) {
            $this->title = $announcement->title;
            $this->details = $announcement->content;
            $this->courseId = $announcement->course_id;
            $this->course = Course::find($this->courseId);

            $attachment = $announcement->attachments->first();

            
            $this->existingAttachment = $attachment ? [
                'path' => $attachment->file_path,
                'name' => $attachment->original_name,
            ] : null;
        }
    }

    public function resetForm()
    {
        if (!$this->announcementId) return;

        $this->loadAnnouncement($this->announcementId);

        // Clear any new uploads
        $this->attachments = [];
        $this->uploadKey = uniqid();

        // Clear validation errors
        $this->resetErrorBag();
        $this->resetValidation();

        $this->dispatch('announcement-reset', id: $this->announcementId);
    }

    public function updateAnnouncement()
    {
        $this->validate();

        $announcement = Announcement::find($this->announcementId);

        if (!$announcement) {
            return;
        }

        $announcement->update([
            'title' => $this->title,
            'content' => $this->details,
        ]);

        if (!empty($this->attachments)) {
            if (!Storage::disk('public')->exists('course_attachments')) {
                Storage::disk('public')->makeDirectory('course_attachments');
            }

            // Delete old file if exists
            if ($this->existingAttachment && Storage::disk('public')->exists($this->existingAttachment['path'])) {
                Storage::disk('public')->delete($this->existingAttachment['path']);
            }

            // Save new files
            foreach ($this->attachments as $file) {
                $path = $file->store('course_attachments', 'public');

                AnnouncementAttachment::updateOrCreate(
                    ['announcement_id' => $announcement->id],
                    [
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]
                );

                $this->existingAttachment = [
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                ];
            }
        }

        $this->uploadKey = uniqid();

        $this->dispatch('swal:success', [
            'title' => 'Success!',
            'text' => 'Announcement updated successfully.',
            'icon' => 'success',
            'button' => 'OK'
        ]);

        $this->dispatch('announcement-updated');
    }

    public function render()
    {
        return view('livewire.modals.implementor.edit-announcement');
    }
}

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
    public $attachments = null; // single file but still called attachments
    public $existingAttachment = null;
    public $removeAttachment = false; // NEW flag
    public $uploadKey;
    public $userId;

    protected $rules = [
        'title' => 'required|string|max:255',
        'details' => 'required|string',
        'attachments' => 'nullable|file|max:102400', // only 1
    ];

    protected $listeners = ['refresh-announcement-modal' => '$refresh'];

    public function mount($announcementId = null)
    {
        $this->uploadKey = uniqid();
        $this->userId = auth()->check() ? auth()->id() : 2;

        if ($announcementId) $this->loadAnnouncement($announcementId);
    }

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


            $this->removeAttachment = false;
        }
    }

    public function resetForm()
    {
        if (!$this->announcementId) return;

        $this->loadAnnouncement($this->announcementId);
        $this->attachments = null;
        $this->uploadKey = uniqid();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('announcement-reset', id: $this->announcementId);
    }

    public function updateAnnouncement()
{
    $this->validate();

    $announcement = Announcement::find($this->announcementId);
    if (!$announcement) return;

    $announcement->update([
        'title' => $this->title,
        'content' => $this->details,
    ]);

    // Remove old attachment if flagged
    if ($this->removeAttachment && $this->existingAttachment) {
        if (Storage::disk('public')->exists($this->existingAttachment['path'])) {
            Storage::disk('public')->delete($this->existingAttachment['path']);
        }
        AnnouncementAttachment::where('announcement_id', $announcement->id)->delete();
        $this->existingAttachment = null;
    }

    // Save new attachment if uploaded
    if ($this->attachments) {
        // Delete existing file if any
        if ($this->existingAttachment && Storage::disk('public')->exists($this->existingAttachment['path'])) {
            Storage::disk('public')->delete($this->existingAttachment['path']);
            AnnouncementAttachment::where('announcement_id', $announcement->id)->delete();
        }

        $path = $this->attachments->store('announcement_attachments', 'public');

        AnnouncementAttachment::create([
            'announcement_id' => $announcement->id,
            'file_path' => $path,
            'original_name' => $this->attachments->getClientOriginalName(),
        ]);

        $this->existingAttachment = [
            'path' => $path,
            'name' => $this->attachments->getClientOriginalName(),
        ];
    }

    // Reset flags
    $this->attachments = null;
    $this->removeAttachment = false;
    $this->uploadKey = uniqid();

    $this->dispatch('announcement-updated');
}


    public function render()
    {
        return view('livewire.modals.implementor.edit-announcement');
    }
}

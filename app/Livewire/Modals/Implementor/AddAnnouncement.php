<?php

namespace App\Livewire\Modals\Implementor;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Announcement;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class AddAnnouncement extends Component
{
    public $courseId;
    public $course;
    public $title;
    public $description;
    public $attachment;
public $userId;


public function mount($courseId = null)
{
    $this->userId = auth()->check() ? auth()->id() : 2;

    if ($courseId) {
        $this->courseId = $courseId;
    } elseif (request()->route('course')) {
        $courseParam = request()->route('course');
        $this->courseId = is_object($courseParam) ? $courseParam->id : $courseParam;
    }

    // Fetch the Course model (if ID exists)
    $this->course = $this->courseId ? Course::find($this->courseId) : null;
}

    public function openAddAnnouncementModal($courseId)
    {
        $this->courseId = $courseId;
        $this->course = Course::find($courseId);

        $this->dispatchBrowserEvent('open-announcement-modal');
    }

    public function render()
    {
        return view('livewire.modals.implementor.add-announcement');
    }
}

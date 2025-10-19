<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Announcement;

class DeleteAnnouncement extends Component
{
    public $courseId;
    public $announcementId;
    public $showModal = false;

    public function mount($courseId, $announcementId)
    {
        $this->courseId = $courseId;
        $this->announcementId = $announcementId;
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

   public function deleteAnnouncement($announcementId)
{
    $announcement = Announcement::find($announcementId);
    
    if ($announcement) {
        $announcement->delete();

        // SweetAlert success message
        $this->dispatch('swal:success', [
            'title' => 'Deleted!',
            'text'  => 'The announcement has been deleted successfully.',
            'icon'  => 'success',
            'button' => 'OK',
        ]);

        // Optional refresh event for your parent list
        $this->dispatch('announcementDeleted');
    } else {
        $this->dispatch('swal:error', [
            'title' => 'Error',
            'text'  => 'Announcement not found.',
            'icon'  => 'error',
            'button' => 'OK',
        ]);
    }
}


    public function render()
    {
        return view('livewire.implementors.delete-announcement');
    }
}

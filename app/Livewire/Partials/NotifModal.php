<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Import User model

class NotifModal extends Component
{
    use WithPagination;

    public $isOpen = false;

    #[On('open-notifications-modal')]
    public function openModal()
    {
        $this->isOpen = true;
        $this->resetPage(); 
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function markAsRead($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->markAsRead();
                $this->dispatch('notificationMarkedAsRead'); 
            }
        }
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
            $this->dispatch('notificationMarkedAsRead'); 
        }
    }

    public function delete($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($id);
            if ($notification) {
                $notification->delete();
                $this->dispatch('notificationMarkedAsRead');
            }
        }
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notifications = $user
            ? $user->notifications()->latest()->paginate(10)
            : collect();

        return view('livewire.partials.notif-modal', [
            'allNotifications' => $notifications
        ]);
    }
}
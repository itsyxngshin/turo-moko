<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Livewire\WithPagination; // Import Pagination
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NavNotif extends Component
{
    use WithPagination; // Use Pagination

    protected $listeners = ['notificationMarkedAsRead' => '$refresh'];
    
    // State for the modal
    public $showAllNotificationsModal = false;

    // Reset pagination when the modal is closed so it re-opens on page 1
    public function updatedShowAllNotificationsModal($value)
    {
        if (!$value) {
            $this->resetPage();
        }
    }

    public function openModal()
    {
        $this->showAllNotificationsModal = true;
    }

    public function closeModal()
    {
        $this->showAllNotificationsModal = false;
    }

    // This property remains for the "Dropdown" preview (top 5)
    public function getNotificationsProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) return collect(); 

        return $user->unreadNotifications()->latest()->take(5)->get();
    }

    public function getUnreadCountProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) return 0;

        return $user->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
            }
        }
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // We fetch "All" notifications only if the modal is open to save performance
        $allNotifications = ($this->showAllNotificationsModal && $user) 
            ? $user->notifications()->latest()->paginate(10) 
            : [];

        return view('livewire.partials.nav-notif', [
            'allNotifications' => $allNotifications
        ]);
    }
}
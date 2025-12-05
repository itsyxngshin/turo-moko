<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NavNotif extends Component
{
    protected $listeners = ['notificationMarkedAsRead' => '$refresh'];

    public function getNotificationsProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) return collect(); 

        // FIX: Removed the () after $user
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
        return view('livewire.partials.nav-notif');
    }
}
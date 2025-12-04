<?php

namespace App\Livewire\Partials;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NavNotif extends Component
{
    // Poll for new notifications every 10 seconds
    protected $listeners = ['notificationMarkedAsRead' => '$refresh'];

    public function getNotificationsProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user) return collect(); // Return empty collection if guest

        // Now Intelephense knows $user has the Notifiable trait
        return User::unreadNotifications()->latest()->take(5)->get();
    }

    public function getUnreadCountProperty()
    {

        $user = Auth::user();

        if (!$user) return 0;

        return User::unreadNotifications()->count();
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

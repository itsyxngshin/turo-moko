<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Notifications extends Component
{
     public $notifications = [];

    public function mount()
    {
        // For now, we’ll use sample data.
        // Later, you can replace this with Notification::where('user_id', auth()->id())->get();
        $this->notifications = [
            [
                'id' => 1,
                'type' => 'course',
                'icon' => 'book-open',
                'message' => 'A new course “Introduction to AI” is now available.',
                'time' => '2 mins ago',
                'color' => 'indigo',
                'read' => false,
            ],
            [
                'id' => 2,
                'type' => 'achievement',
                'icon' => 'check-circle',
                'message' => 'You successfully completed Module 2: Basics of HTML.',
                'time' => '15 mins ago',
                'color' => 'green',
                'read' => false,
            ],
            [
                'id' => 3,
                'type' => 'announcement',
                'icon' => 'megaphone',
                'message' => 'Admin posted: “Maintenance scheduled on Nov 10, 10PM.”',
                'time' => '1 hour ago',
                'color' => 'purple',
                'read' => true,
            ],
            [
                'id' => 4,
                'type' => 'message',
                'icon' => 'message-circle',
                'message' => 'Maria sent you a message: “Can you share your project file?”',
                'time' => '2 hours ago',
                'color' => 'blue',
                'read' => false,
            ],
        ];
    }

    public function markAsRead($id)
    {
        foreach ($this->notifications as &$notif) {
            if ($notif['id'] === $id) {
                $notif['read'] = true;
                break;
            }
        }
    }

    public function markAllAsRead()
    {
        foreach ($this->notifications as &$notif) {
            $notif['read'] = true;
        }
    }

    public function render()
    {
        return view('livewire.learner.notifications');
    }
}
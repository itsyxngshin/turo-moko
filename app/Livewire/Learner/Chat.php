<?php

namespace App\Livewire\Learner;

use Livewire\Component;

class Chat extends Component
{
    public $messages = [
        ['user' => 'Advisor', 'text' => 'Hello there! How can I help you today?', 'type' => 'incoming'],
        ['user' => 'You', 'text' => 'Hi! I just wanted to ask about my current course progress.', 'type' => 'outgoing'],
        ['user' => 'Advisor', 'text' => 'Sure! You’re currently 75% done with your module. Keep it up!', 'type' => 'incoming'],
    ];

    public $newMessage = '';

    public function sendMessage()
    {
        if (trim($this->newMessage) === '') return;

        $this->messages[] = [
            'user' => 'You',
            'text' => $this->newMessage,
            'type' => 'outgoing',
        ];

        $this->newMessage = '';
    }

    public function render()
    {
        return view('livewire.learner.chat');
    }
}

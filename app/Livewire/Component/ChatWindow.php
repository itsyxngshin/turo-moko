<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatWindow extends Component
{

     public $conversationId;
    public $messages;
    public $body;
    public $typingUser = null;

    protected $listeners = ['conversationSelected' => 'loadConversation'];

    public function loadConversation($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->loadMessages();

        // Mark unread messages as read
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->update(['is_read' => true]);
    }

    public function loadMessages()
    {
        $this->messages = Message::where('conversation_id', $this->conversationId)
            ->with('sender')
            ->orderBy('created_at')
            ->get();
    }

    public function updatedBody()
    {
        broadcast(new \App\Events\TypingIndicator($this->conversationId, Auth::user()))->toOthers();
    }

    public function sendMessage()
    {
        $message = Message::create([
            'conversation_id' => $this->conversationId,
            'sender_id' => Auth::id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->messages->push($message);

        broadcast(new \App\Events\MessageSent($message))->toOthers();
    }

    public function render()
    {
        return view('livewire.component.chat-window');
    }
}

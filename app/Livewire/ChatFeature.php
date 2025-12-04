<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Conversation;
use Livewire\Attributes\On;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('layouts.chat-layout')]

class ChatFeature extends Component
{
    public Conversation $conversation;
    public $messages;
    public $content = '';

    protected $listeners = [];
    public $selectedConversationId;

    // This listener catches the event from your ConversationList sidebar
    #[On('conversationSelected')]
    public function loadConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
    }

    public function mount(Conversation $conversation)
    {
        $this->conversation = $conversation;
        $this->messages = $conversation->messages()->with('sender')->latest()->take(20)->get()->reverse();

        $this->listeners = [
            "echo-private:chat.{$conversation->id},MessageSent" => 'messageReceived',
        ];
    }

    public function sendMessage()
    {
        if (trim($this->content) === '') return;

        $message = Message::create([ 
            'conversation_id' => $this->conversation->id,
            'sender_id' => Auth::id(),
            'content' => $this->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        $this->messages->push($message->load('sender'));
        $this->content = '';
    }

    public function messageReceived($event)
    {
        $this->messages->push(Message::find($event['message']['id']));
    }

    public function render()
    {
        return view('livewire.chat-feature');
    }
}

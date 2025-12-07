<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSent;

class Chat extends Component
{
    public Conversation $conversation;
    public $messages;
    public $content = '';

    protected $listeners = [];

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
        return view('livewire.learner.chat');
    }
}

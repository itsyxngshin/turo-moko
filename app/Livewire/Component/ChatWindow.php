<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\Message;
use Livewire\Attributes\On;
use App\Models\Conversation; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatWindow extends Component
{
    public $conversationId;
    public $messages;
    public $body;
    public $typingUser = null;
    public $partner;

    protected $listeners = ['conversationSelected' => 'loadConversation'];
    // 2. Add this Listener Method
    // This tells Livewire: "When you hear 'MessageSent' on the channel 'chat.123', run this function"
    // 1. ADD THIS METHOD
    public function getListeners()
    {
        // If no chat is selected, don't try to listen (prevents the error)
        if (!$this->conversationId) {
            return [];
        }

        // Dynamically register the listener using the current ID
        return [
            "echo-private:chat.{$this->conversationId},MessageSent" => 'listenForMessage',
        ];
    }
    public function listenForMessage($event)
    {
        // Safety check
        if (!isset($event['message']) || $event['message']['conversation_id'] !== $this->conversationId) {
            return;
        }

        // 1. Convert the Array to an Eloquent Model (Object)
        $newMessage = new Message($event['message']);

        // 2. CRITICAL: Fix the Date Format
        // Event data sends dates as Strings, but Blade expects Carbon objects for ->format()
        // If we don't fix this, you'll get a "Call to member function format() on string" error next.
        if (isset($event['message']['created_at'])) {
            $newMessage->created_at = Carbon::parse($event['message']['created_at']);
        }

        // 3. Push the Object (not the array)
        $this->messages->push($newMessage);
        
        $this->dispatch('message-sent');
        $this->dispatch('refresh-conversation-list');
    }

    public function mount($conversationId)
    {
        $this->conversationId = $conversationId;
        
        // CRITICAL FIX: Load the conversation & partner immediately upon mount
        $this->loadConversation($conversationId);
    }
    public function loadConversation($conversationId)
    {
        $this->conversationId = $conversationId;
        $this->partner; 
        
        // 2. FETCH THE CONVERSATION AND DETERMINE THE PARTNER
        $conversation = Conversation::with(['userOne.profile', 'userTwo.profile'])
            ->findOrFail($conversationId);

        $this->partner = $conversation->user_one_id === Auth::id()
            ? $conversation->userTwo
            : $conversation->userOne;

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
            'content' => $this->body,
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

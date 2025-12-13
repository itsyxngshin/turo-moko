<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\Conversation;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ConversationList extends Component
{
    public $selectedConversationId = null;
    public $isSearching = false;

    // We don't need loadConversations() anymore since render() handles it
    public function mount()
    {
        // Keep empty or handle other setup
    }

    #[On('startConversation')]
    public function startConversation($userId)
    {
        // 1. Check if conversation exists
        $conversation = Conversation::where(function($q) use ($userId) {
            $q->where('user_one_id', Auth::id())->where('user_two_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('user_one_id', $userId)->where('user_two_id', Auth::id());
        })->first();

        // 2. Create if missing
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => Auth::id(),
                'user_two_id' => $userId
            ]);
        }

        $this->isSearching = false;
        // The list will auto-refresh because render() runs next
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch('conversationSelected', $conversationId);
    }

    public function render()
    {
        // ✅ FETCH DATA HERE
        // This ensures the 'orderBy desc' constraint is applied on every render/click
        $conversations = Conversation::where('user_one_id', Auth::id())
            ->orWhere('user_two_id', Auth::id())
            ->with([
                'userOne.profile.photo', 
                'userTwo.profile.photo', 
                'messages' => function($query) {
                    $query->orderBy('created_at', 'desc'); // Newest First
                }
            ])
            ->latest('updated_at')
            ->get();

        return view('livewire.component.conversation-list', [
            'conversations' => $conversations
        ]);
    }
}
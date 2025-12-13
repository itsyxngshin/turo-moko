<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ConversationList extends Component
{
    public $conversations;
    public $selectedConversationId = null;
    
    // NEW: Search properties
    public $isSearching = false;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = Conversation::where('user_one_id', Auth::id())
            ->orWhere('user_two_id', Auth::id())
            ->with([
                'userOne.profile.photo', 
                'userTwo.profile.photo', 
                // FIXED: Sort by Newest First so ->first() gives the latest message
                'messages' => function($query) {
                    $query->orderBy('created_at', 'desc'); 
                }
            ])
            ->latest('updated_at')
            ->get();
    }

    // NEW: Start or open conversation
    #[On('startConversation')]
    public function startConversation($userId)
    {
        // 1. Check if conversation already exists
        $conversation = Conversation::where(function($q) use ($userId) {
            $q->where('user_one_id', Auth::id())->where('user_two_id', $userId);
        })->orWhere(function($q) use ($userId) {
            $q->where('user_one_id', $userId)->where('user_two_id', Auth::id());
        })->first();

        // 2. If not, create it
        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => Auth::id(),
                'user_two_id' => $userId
            ]);
        }

        $this->isSearching = false;
        
        // 4. Reload list to show the new/updated conversation at top
        $this->loadConversations();
        
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->dispatch('conversationSelected', $conversationId);
    }

    public function render()
    {
        return view('livewire.component.conversation-list');
    }
}
<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class ConversationList extends Component
{
    public $conversations;
    public $selectedConversationId = null;
    
    // NEW: Search properties
    public $searchQuery = '';
    public $searchResults = [];
    public $isSearching = false;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = Conversation::where('user_one_id', Auth::id())
            ->orWhere('user_two_id', Auth::id())
            ->with(['userOne', 'userTwo', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();
    }

    public function updatedSearchQuery()
    {
        // 1. If search is too short, hide the dropdown
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        // 2. Search Users via Profile (First/Last Name) or Email
        $this->searchResults = User::where('id', '!=', Auth::id())
            ->with('profile') // Eager load to avoid N+1 queries
            ->where(function(Builder $query) {
                $query->whereHas('profile', function(Builder $q) {
                    $q->where('first_name', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            })
            ->limit(5) // Limit to 5 results so the dropdown doesn't get too long
            ->get();
    }

    // NEW: Start or open conversation
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

        // 3. Reset search and select
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isSearching = false;
        
        // 4. Reload list to show the new/updated conversation at top
        $this->loadConversations(); 
        
        $this->selectConversation($conversation->id);
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
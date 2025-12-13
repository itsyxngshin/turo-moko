<?php

namespace App\Livewire\Component;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class UserSearch extends Component
{
    public $searchQuery = '';
    public $searchResults = [];

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = User::where('id', '!=', Auth::id())
            // FIXED: Eager load profile AND photo to avoid N+1 queries
            ->with('profile.photo') 
            ->where(function(Builder $query) {
                $query->whereHas('profile', function(Builder $q) {
                    $q->where('first_name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchQuery . '%');
                })
                ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            })
            ->limit(5)
            ->get();
    }

    public function selectUser($userId)
    {
        $this->dispatch('startConversation', userId: $userId);
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.component.user-search');
    }
}
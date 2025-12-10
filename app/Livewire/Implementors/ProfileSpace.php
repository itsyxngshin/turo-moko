<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Course;

class ProfileSpace extends Component
{
    use WithPagination;

    public $user;
    public $username;
    public $active_engagement;
    
    // Search/Sort for the public viewer to filter this teacher's courses
    public $search = '';
    public $sort = 'latest';

    public function mount($username)
    {
        // 1. Find the user by username (404 if not found)
        $this->user = User::where('username', $username)
            ->with([
                'profile.portfolioSets.workPortfolio', 
                'engagements',
                'profile.photo' // Ensure photo is loaded
            ])
            ->firstOrFail();

        $this->username = $username;
        $this->active_engagement = $this->user->engagements->last();
    }

    public function updatedSearch() { $this->resetPage(); }
    public function updatedSort() { $this->resetPage(); }

    public function render()
    {
        // 2. Fetch ONLY this user's active courses
        $query = Course::query()
            ->where('implementer_id', $this->user->id)
            ->where('status', 'Active')
            ->with(['coverPhotos', 'category', 'organization', 'tags']);

        if ($this->search) {
            $query->where('course_title', 'like', '%'.$this->search.'%');
        }

        switch ($this->sort) {
            case 'oldest': $query->oldest('start_date'); break;
            case 'a-z':    $query->orderBy('course_title', 'asc'); break;
            default:       $query->latest('start_date'); break;
        }

        return view('livewire.implementors.profile-space', [
            'courses' => $query->paginate(6)
        ])->layout('layouts.layout'); // Use your main layout
    }
}

<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\User;
use App\Models\CourseEnrollee;

class LiveSearchStudents extends Component
{
    public $course;           // Course object passed from parent
    public $searchName = '';  // Search input
    public $searchResults = []; // Matching students
    
    // NEW: Array to hold students selected but not yet saved
    public $stagedUsers = []; 

    public function updatedSearchName()
    {
        $query = $this->searchName;

        if (strlen($query) >= 2) {
            // Get IDs of users already enrolled to exclude them from search
            $existingIds = CourseEnrollee::where('course_id', $this->course->id)
                ->pluck('enrollee_id')
                ->toArray();
            
            // Get IDs of users currently in the staging area to exclude them
            $stagedIds = array_column($this->stagedUsers, 'id');
            $excludedIds = array_merge($existingIds, $stagedIds);

            $this->searchResults = User::where('role_id', 1) // learners only
                ->whereNotIn('id', $excludedIds) // Exclude existing/staged
                ->where(function($q) use ($query) {
                    $q->whereHas('profile', function($q2) use ($query) {
                        $q2->where('first_name', 'like', "%{$query}%")
                           ->orWhere('middle_name', 'like', "%{$query}%")
                           ->orWhere('last_name', 'like', "%{$query}%");
                    })
                    ->orWhere('username', 'like', "%{$query}%");
                })
                ->with('profile')
                ->take(10) // Limit results for performance
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    // NEW: Add user to temporary list instead of DB
    public function stageUser($userId)
    {
        $user = User::with('profile')->find($userId);

        if ($user) {
            // Add to staged array
            $this->stagedUsers[] = [
                'id' => $user->id,
                'name' => $user->profile->first_name . ' ' . $user->profile->last_name,
                'username' => $user->username
            ];
        }

        // Clear search to allow adding next student immediately
        $this->searchName = '';
        $this->searchResults = [];
    }

    // NEW: Remove from temporary list
    public function unstageUser($index)
    {
        unset($this->stagedUsers[$index]);
        $this->stagedUsers = array_values($this->stagedUsers); // Re-index array
    }

    // NEW: Save all staged users to DB
    public function enrollStaged()
    {
        if (empty($this->stagedUsers)) return;

        foreach ($this->stagedUsers as $user) {
            // Double check existence to prevent errors
            $exists = CourseEnrollee::where('course_id', $this->course->id)
                ->where('enrollee_id', $user['id'])
                ->exists();

            if (!$exists) {
                CourseEnrollee::create([
                    'course_id' => $this->course->id,
                    'enrollee_id' => $user['id'],
                    'enrollment_date' => now(),
                    'status' => 'Active',
                ]);
            }
        }

        session()->flash('success', count($this->stagedUsers) . ' enrollees added successfully.');
        
        $this->stagedUsers = []; // Clear list
    }

    // Remove enrollee (Existing logic)
    public function removeEnrollee($enrolleeId)
    {
        CourseEnrollee::where('id', $enrolleeId)->delete();
        session()->flash('success', 'Enrollee removed successfully.');
    }

    public function getEnrolleesProperty()
    {
        return CourseEnrollee::where('course_id', $this->course->id)
            ->with('user.profile')
            ->latest('enrollment_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.implementors.live-search-students');
    }
}
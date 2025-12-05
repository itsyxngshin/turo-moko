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
    public $loading = false;

    // Called automatically whenever $searchName is updated
    public function updatedSearchName()
    {
        $query = $this->searchName;

        if (strlen($query) >= 2) { // Minimum 2 chars to search
            $this->searchResults = User::where('role_id', 1) // learners only
                ->where(function($q) use ($query) {
                    $q->whereHas('profile', function($q2) use ($query) {
                        $q2->where('first_name', 'like', "%{$query}%")
                           ->orWhere('middle_name', 'like', "%{$query}%")
                           ->orWhere('last_name', 'like', "%{$query}%");
                    })
                    ->orWhere('username', 'like', "%{$query}%");
                })
                ->with('profile')
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    // Add enrollee
    public function addEnrollee($userId)
    {
        $this->loading = true;

        $exists = CourseEnrollee::where('course_id', $this->course->id)
            ->where('enrollee_id', $userId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'User is already enrolled.');
            $this->loading = false;
            return;
        }

        CourseEnrollee::create([
            'course_id' => $this->course->id,
            'enrollee_id' => $userId,
            'enrollment_date' => now(),
            'status' => 'Active',
        ]);

        session()->flash('success', 'Enrollee added successfully.');

        // Clear search
        $this->searchName = '';
        $this->searchResults = [];

        $this->loading = false;
    }

    // Remove enrollee
    public function removeEnrollee($enrolleeId)
    {
        $this->loading = true;

        CourseEnrollee::where('id', $enrolleeId)->delete();

        session()->flash('success', 'Enrollee removed successfully.');

        $this->loading = false;
    }

    // Computed property for enrollees table
    public function getEnrolleesProperty()
    {
        return CourseEnrollee::where('course_id', $this->course->id)
            ->with('user.profile')
            ->get();
    }

    public function render()
    {
        return view('livewire.implementors.live-search-students');
    }
}

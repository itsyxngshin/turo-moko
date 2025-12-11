<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CourseEnrollee; // <--- MAKE SURE THIS IS IMPORTED

class Enrollees extends Component
{
    use WithPagination;
    
    // ... search/reset logic ...

    public function render()
    {
        // CORRECT: Query the Pivot Table directly
        $query = CourseEnrollee::query() 
            ->with(['enrollee.profile.photo', 'course']); // Load the relationships defined above

        // ... search logic ...

        return view('livewire.admin.enrollees', [
            'enrollees' => $query->latest('enrollment_date')->paginate(10)
        ])->layout('layouts.layout');
    }
}
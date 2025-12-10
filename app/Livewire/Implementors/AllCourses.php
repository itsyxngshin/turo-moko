<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class AllCourses extends Component
{
    public $courses;

    public function mount()
    {
        $this->courses = Course::with([
                'category', 
                'organization', 
                'tags', 
                'activeCoverPhoto', 
                'implementer' // ✅ ADD THIS
            ])
            ->where('implementer_id', Auth::id()) 
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.implementors.all-courses')->layout('layouts.layout');
    }
}

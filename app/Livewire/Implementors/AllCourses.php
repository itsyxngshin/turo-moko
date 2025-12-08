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
        $user = Auth::user();

        if ($user) {
            $this->courses = Course::with(['coverPhotos', 'category', 'tags', 'organization'])
                ->where('implementer_id', $user->id)
                ->latest()
                ->get();
        } else {
            $this->courses = collect();
        }
    }

    public function render()
    {
        return view('livewire.implementors.all-courses')->layout('layouts.layout');
    }
}

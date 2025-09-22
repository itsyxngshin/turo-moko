<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\User;

class Profile extends Component
{
    public $studentName;
    public $description;
    public $email;
    public $activeCourses;
    public $archivedCourses;
    public $courses;

    public function mount()
    {
        $user = auth()->user(); // adjust based on auth logic

        $this->studentName = $user->name;
        $this->description = $user->description ?? 'No description provided';
        $this->email = $user->email;

        $this->activeCourses = $user->courses()->where('status', 'active')->count();
        $this->archivedCourses = $user->courses()->where('status', 'archived')->count();

        // Fetch all courses including images
        $this->courses = $user->courses()->latest()->get()->map(function ($course) {
            return [
                'name' => $course->name,
                'semester' => $course->semester ?? 'N/A',
                'description' => $course->description ?? 'No description',
                'image' => $course->image ?? '/images/course1.jpg', // dynamic image
                'progress' => $course->pivot->progress ?? 0, // if using pivot table
            ];
        });
    }

    public function render()
    {
        return view('livewire.learner.profile');
    }
}

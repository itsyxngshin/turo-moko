<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth; 

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

    public function enroll($courseId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $learnerId = Auth::id();
        // Use findOrFail to ensure the course exists
        $course = Course::findOrFail($courseId);

        // 1. Check if already enrolled
        $alreadyEnrolled = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $learnerId)
            ->exists();

        // If already enrolled, redirect immediately using Route Model Binding
        if ($alreadyEnrolled) {
            // Laravel automatically uses 'course_code' because of your route definition
            return redirect()->route('course.show', $course); 
        }

        // 2. Check capacity
        $currentCount = $course->enrollees()->count();
        if ($currentCount >= $course->student_limit) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Course Full',
                'text' => 'Sorry, this course has reached its maximum capacity.',
            ]);
            return;
        }

        // 3. Enroll User
        CourseEnrollee::create([
            'course_id' => $course->id,
            'enrollee_id' => $learnerId,
            'enrollment_date' => now(),
            'status' => 'Active',
        ]);

        // 4. Flash success and Redirect
        session()->flash('message', 'You have successfully joined the class!');
        
        return redirect()->route('learner.course.show', $course);
    }

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

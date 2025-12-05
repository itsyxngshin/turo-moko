<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Classes extends Component
{
    public $activeCourses;          
    public $activeCoursesCount;     
    public $completedCourses;       
    public $completedCoursesCount;  

    public $courses;         
    public $featuredCourse;  

    public $pendingActivities;
    public $pendingEvaluations;
    public $recentCourses;

    public function mount()
    {
        /** @var \App\Models\User $user */ // <--- ADD THIS LINE
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        // 1. Active Courses 
        $this->activeCourses = $user->enrolledCourses()
            ->where('courses.status', 'active') 
            ->wherePivot('status', 'Active')    
            ->get();

        $this->activeCoursesCount = $this->activeCourses->count();

        // 2. Completed Courses
        $this->completedCoursesCount = $user->enrolledCourses()
            ->wherePivot('status', 'Completed')
            ->count();
        
        $this->completedCourses = $user->enrolledCourses()
            ->wherePivot('status', 'Completed')
            ->get();

        // 3. Pending assignments
        if (class_exists(Assignment::class)) {
            $courseIds = $this->activeCourses->pluck('id');

            $this->pendingActivities = Assignment::whereIn('course_id', $courseIds)
                ->where('status', 'Pending')
                ->count();
                
            $this->pendingEvaluations = Assignment::whereIn('course_id', $courseIds)
                ->where('status', 'Evaluation Pending')
                ->count();
        } else {
            $this->pendingActivities = 0;
            $this->pendingEvaluations = 0;
        }

        // 4. Featured Course
        $this->featuredCourse = $user->enrolledCourses()
            ->where('courses.status', 'active')
            ->latest('course_enrollees.enrollment_date')
            ->first();

        // 5. Recent Courses
        $this->recentCourses = $user->enrolledCourses()
            ->latest('course_enrollees.enrollment_date')
            ->take(5)
            ->get();
            
        // 6. "All" Courses
        $this->courses = $this->activeCourses;
    }

    public function render()
    {
        return view('livewire.learner.classes');
    }
}
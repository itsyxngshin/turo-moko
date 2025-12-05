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
        $user = Auth::user();

        // 1. Active Courses 
        // Logic: The Course itself must be 'active' AND the student's enrollment status must be 'Active'
        $this->activeCourses = $user->enrolledCourses()
            ->where('courses.status', 'active') // Check Course Table status
            ->wherePivot('status', 'Active')    // Check Enrollees Table status
            ->get();

        $this->activeCoursesCount = $this->activeCourses->count();

        // 2. Completed Courses
        // Logic: Student's enrollment status is 'Completed'
        $this->completedCoursesCount = $user->enrolledCourses()
            ->wherePivot('status', 'Completed')
            ->count();
        
        // (Optional) If you want the actual list of completed courses
        $this->completedCourses = $user->enrolledCourses()
            ->wherePivot('status', 'Completed')
            ->get();

        // 3. Pending assignments (Scoped to the user's courses)
        if (class_exists(Assignment::class)) {
            // This logic assumes Assignments are linked to Courses. 
            // We verify the user is enrolled in the course that has the assignment.
            $this->pendingActivities = Assignment::whereIn('course_id', $this->activeCourses->pluck('id'))
                ->where('status', 'Pending')
                ->count();
                
            $this->pendingEvaluations = Assignment::whereIn('course_id', $this->activeCourses->pluck('id'))
                ->where('status', 'Evaluation Pending')
                ->count();
        } else {
            $this->pendingActivities = 0;
            $this->pendingEvaluations = 0;
        }

        // 4. Featured Course (Logic: Latest active course the user is enrolled in)
        $this->featuredCourse = $user->enrolledCourses()
            ->where('courses.status', 'active')
            ->latest('course_enrollees.enrollment_date')
            ->first();

        // 5. Recent Courses (Last 5 enrolled)
        $this->recentCourses = $user->enrolledCourses()
            ->latest('course_enrollees.enrollment_date')
            ->take(5)
            ->get();
            
        // 6. "All" Courses usually implies the user's active list in this context
        $this->courses = $this->activeCourses;
    }

    public function render()
    {
        return view('livewire.learner.classes');
    }
}

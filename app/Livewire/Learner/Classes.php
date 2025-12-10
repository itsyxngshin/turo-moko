<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\ProgramEvaluation;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class Classes extends Component
{
    public $activeCourses;
    public $activeCoursesCount;
    public $completedCourses;
    public $completedCoursesCount;
    public $pendingActivities;
    public $pendingEvaluations;
    public $featuredCourse;
    public $recentCourses;
    public $courses;

    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('auth.login');
        }

        // Active Courses
        $this->activeCourses = $user->enrolledCourses()
            ->where('courses.status', 'active')
            ->wherePivot('status', 'active')
            ->get();
        $this->activeCoursesCount = $this->activeCourses->count();

       // Completed Courses
        $this->completedCourses = $user->enrolledCourses()
            ->wherePivot('status', 'completed')
            ->get();

        $this->completedCoursesCount = $this->completedCourses->count();

        // Pending assignments
        $courseIds = $this->activeCourses->pluck('id');
        $this->pendingActivities = Assignment::whereIn('course_id', $courseIds)
            ->where('status', 'Pending')
            ->count();
        
        // Pending evaluations - count courses where learner hasn't submitted evaluation yet
        $enrolleeIds = CourseEnrollee::where('enrollee_id', $user->id)
            ->whereIn('course_id', $courseIds)
            ->pluck('id');
            
        $completedEvalCount = ProgramEvaluation::whereIn('enrollee_id', $enrolleeIds)
            ->whereNotNull('submitted_at')
            ->count();
            
        // Pending = total active courses - completed evaluations
        $this->pendingEvaluations = max(0, $this->activeCoursesCount - $completedEvalCount);

        // Featured Course (latest active)
        $this->featuredCourse = $this->activeCourses->sortByDesc('pivot.enrollment_date')->first();

        // Recent Courses (latest 5)
        $this->recentCourses = $user->enrolledCourses()
            ->latest('course_enrollees.enrollment_date')
            ->take(5)
            ->get();

        // All active courses for display
        $this->courses = $this->activeCourses;
    }

    public function render()
    {
        return view('livewire.learner.classes');
    }
}

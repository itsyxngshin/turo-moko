<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\CourseEnrollee;
use App\Models\Quiz;
use App\Models\Evaluation;
use App\Models\CourseFeedback;
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
        redirect()->route('auth.login')->send();
    }

    /* ================= ACTIVE COURSES ================= */
    $this->activeCourses = $user->enrolledCourses()
        ->where('courses.status', 'Active')
        ->where('courses.visibility', 'Visible')
        ->wherePivot('status', 'Active') // ✅ Only Active enrollee
        ->with(['category', 'implementer.profile', 'activeCoverPhoto'])
        ->get();

    $this->activeCoursesCount = $this->activeCourses->count();

    /* ================= COMPLETED COURSES ================= */
    $this->completedCourses = $user->enrolledCourses()
        ->where('courses.status', 'Active')
        ->where('courses.visibility', 'Visible')
        ->wherePivot('status', 'Completed') // ✅ Only Completed enrollee
        ->with(['category', 'implementer.profile', 'activeCoverPhoto'])
        ->get();

    $this->completedCoursesCount = $this->completedCourses->count();

    /* ================= PENDING ASSIGNMENTS ================= */
    $courseIds = $this->activeCourses->pluck('id');

    $enrolleeIds = CourseEnrollee::where('enrollee_id', $user->id)
        ->whereIn('course_id', $courseIds)
        ->pluck('id');

    $pendingAssignments = Assignment::whereIn('course_id', $courseIds)
        ->where('status', 'Open')
        ->whereDoesntHave('submissions', function ($q) use ($enrolleeIds) {
            $q->whereIn('enrollee_id', $enrolleeIds);
        })
        ->count();

    $this->pendingActivities = $pendingAssignments;

    /* ================= PENDING EVALUATIONS ================= */
    $this->pendingEvaluations = $this->activeCourses->filter(function ($course) use ($user) {
        $hasEvaluation = Evaluation::where('course_id', $course->id)->exists();
        $hasFeedback = CourseFeedback::where('course_id', $course->id)
            ->where('learner_id', $user->id)
            ->exists();

        return $hasEvaluation && !$hasFeedback;
    })->count();

/* ================= FEATURED COURSE ================= */
$this->featuredCourse = Course::whereHas('enrollees', function ($q) use ($user) {
        $q->where('users.id', $user->id)
          ->where('course_enrollees.status', 'Active'); // ✅ Correct column
    })
    ->orderByDesc(function($query) use ($user) {
        $query->select('course_enrollees.created_at')
              ->from('course_enrollees')
              ->whereColumn('courses.id', 'course_enrollees.course_id')
              ->where('course_enrollees.enrollee_id', $user->id)
              ->limit(1);
    })
    ->with(['category', 'implementer.profile', 'activeCoverPhoto'])
    ->first();

/* ================= RECENT COURSES ================= */
$this->recentCourses = Course::whereHas('enrollees', function ($q) use ($user) {
        $q->where('users.id', $user->id)
          ->where('course_enrollees.status', 'Active'); // ✅ Correct column
    })
    ->orderByDesc(function($query) use ($user) {
        $query->select('course_enrollees.created_at')
              ->from('course_enrollees')
              ->whereColumn('courses.id', 'course_enrollees.course_id')
              ->where('course_enrollees.enrollee_id', $user->id)
              ->limit(1);
    })
    ->with(['category', 'implementer.profile', 'activeCoverPhoto'])
    ->take(5)
    ->get();


    /* ================= COURSES FOR DISPLAY ================= */
    $this->courses = $this->activeCourses; // ✅ Only Active enrollee
}


    public function render()
    {
        return view('livewire.learner.classes');
    }
}

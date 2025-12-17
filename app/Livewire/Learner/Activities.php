<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class Activities extends Component
{
    public $assignments;

    public function mount()
    {
        $user = Auth::user();

        if (!$user) {
            redirect()->route('auth.login')->send();
        }

        // Get courses the learner is enrolled in
        $courseIds = $user->enrolledCourses()
            ->where('courses.status', 'Active')
            ->where('courses.visibility', 'Visible')
            ->wherePivot('status', 'Active')
            ->pluck('courses.id');

        $enrolleeIds = CourseEnrollee::where('enrollee_id', $user->id)
            ->whereIn('course_id', $courseIds)
            ->pluck('id');

        // Get pending assignments (not past due and not submitted)
        $this->assignments = Assignment::whereIn('course_id', $courseIds)
            ->where('status', 'Open')
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>', now());
            })
            ->whereDoesntHave('submissions', function ($q) use ($enrolleeIds) {
                $q->whereIn('enrollee_id', $enrolleeIds);
            })
            ->with('course')
            ->get()
            ->map(function ($assignment) {
                return (object) [
                    'type' => 'assignment',
                    'title' => $assignment->title,
                    'description' => $assignment->instruction ?? '',
                    'due_date' => $assignment->end_date ? \Carbon\Carbon::parse($assignment->end_date) : null,
                    'course_title' => $assignment->course->course_title ?? 'Unknown Course',
                    'assignment' => $assignment,
                ];
            });
    }

    public function render()
    {
        return view('livewire.learner.activities');
    }
}

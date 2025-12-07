<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;

class AllCourses extends Component
{
    public $instructor;
    public $courses;
    public $enrolleesCount = 0;
    public $submissionsCount = 0;
    public $recentCourse;

    public function mount()
    {
        $this->instructor = auth()->user();

        if ($this->instructor) {
            $this->courses = Course::with(['activeCoverPhoto', 'category'])
                ->where('implementer_id', $this->instructor->id)
                ->latest()
                ->get();

            $this->enrolleesCount = $this->courses->sum('enrollees_count');

            $assignmentIds = Assignment::whereIn('lesson_id', $this->courses->pluck('id'))->pluck('id');
            $this->submissionsCount = Submission::whereIn('assignment_id', $assignmentIds)->count();

            $this->recentCourse = $this->courses->sortByDesc('updated_at')->first();
        } else {
            $this->courses = collect();
            $this->recentCourse = null;
        }
    }

    public function render()
    {
        return view('livewire.implementors.all-courses'); // ⚠ Do NOT pass variables here again, they are public properties
    }
}

<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class ShowAllCourses extends Component
{
    public $search = '';

    public function enroll(Course $course)
    {
        $learnerId = Auth::id();

        $alreadyEnrolled = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $learnerId)
            ->exists();

        $currentCount = $course->enrollees()->count();

        if ($alreadyEnrolled) {
            $this->dispatchBrowserEvent('swal', [
                'icon' => 'info',
                'title' => 'Already Enrolled',
                'text' => 'You are already enrolled in this course.',
            ]);
            return;
        }

        if ($currentCount >= $course->student_limit) {
            $this->dispatchBrowserEvent('swal', [
                'icon' => 'error',
                'title' => 'Course Full',
                'text' => 'Sorry, this course has reached its maximum capacity.',
            ]);
            return;
        }

        CourseEnrollee::create([
            'course_id' => $course->id,
            'enrollee_id' => $learnerId,
            'enrollment_date' => now(),
            'status' => 'Active',
        ]);

        $this->dispatchBrowserEvent('swal', [
            'icon' => 'success',
            'title' => 'Enrolled!',
            'text' => 'You have successfully joined the course.',
        ]);
    }

    public function render()
    {
        $learnerId = Auth::id();

        $suggestedCourses = Course::query()
            ->where('status', 'Active')
            ->where('visibility', 'Visible')
            ->whereDoesntHave('enrollees', function ($query) use ($learnerId) {
                $query->where('course_enrollees.enrollee_id', $learnerId);
            })
            ->when($this->search, function ($query) {
                $query->where('course_title', 'like', '%' . $this->search . '%')
                      ->orWhere('subject', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.learner.show-all-courses', [
            'suggestedCourses' => $suggestedCourses,
        ]);
    }
}

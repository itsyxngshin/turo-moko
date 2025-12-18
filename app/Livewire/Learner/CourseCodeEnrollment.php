<?php

namespace App\Livewire\Learner;

use Livewire\Component;
use App\Models\Course;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class CourseCodeEnrollment extends Component
{
    public string $courseCode = '';

    protected $rules = [
        'courseCode' => 'required|string'
    ];

    public function enrollByCode()
    {
        $this->validate();

        $course = Course::where('course_code', $this->courseCode)
            ->where('status', 'Active')
            ->where('visibility', 'Visible')
            ->first();

        if (!$course) {
            $this->addError('courseCode', 'Invalid or inactive course code.');
            return;
        }

        $learner = Auth::user();

        // Find existing enrollee
        $enrollee = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $learner->id)
            ->first();

        // Already active
        if ($enrollee && $enrollee->status === 'Active') {
            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => 'Notice',
                'text' => 'You are already enrolled in this course.'
            ]);
            return;
        }

        // Check student limit
        $activeCount = CourseEnrollee::where('course_id', $course->id)
            ->where('status', 'Active')
            ->count();

        if ($activeCount >= $course->student_limit) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Course Full',
                'text' => 'This course has reached its student limit.'
            ]);
            return;
        }

        // Reactivate or create
        if ($enrollee) {
            $enrollee->update([
                'status' => 'Active',
                'enrollment_date' => now(),
            ]);
        } else {
            CourseEnrollee::create([
                'course_id' => $course->id,
                'enrollee_id' => $learner->id,
                'status' => 'Active',
                'enrollment_date' => now(),
            ]);
        }

        // ✅ Livewire v3 event
        $this->dispatch('enrolled');

        return redirect()->route('learner.course.show', $course);
    }

    public function render()
    {
        return view('livewire.learner.course-code-enrollment');
    }
}

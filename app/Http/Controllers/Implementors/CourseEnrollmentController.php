<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;

class CourseEnrollmentController extends Controller
{
    // Step 1: Show confirmation page
    public function join(Course $course)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('https://turo-moko.com/login');
        }

        if (!$user->role || strtolower($user->role->role_name) !== 'learner') {
            abort(403, 'Only learners can enroll in courses.');
        }

        if ($course->status !== 'active' || $course->visibility !== 'visible') {
            abort(403, 'This course is not available for enrollment.');
        }

        $alreadyEnrolled = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $user->id)
            ->where('status', 'Active')
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('learner.course.show', $course)
                ->with('success', 'You are already enrolled in this course.');
        }

        return view('livewire.course-enrollment', compact('course'));
    }

    // Step 2: Handle enrollment after confirmation
    public function confirmEnrollment(Course $course)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect('https://turo-moko.com/login');
        }

        if (!$user->role || strtolower($user->role->role_name) !== 'learner') {
            abort(403, 'Only learners can enroll in courses.');
        }

        $enrollee = CourseEnrollee::where('course_id', $course->id)
            ->where('enrollee_id', $user->id)
            ->first();

        $currentEnrollees = CourseEnrollee::where('course_id', $course->id)
            ->where('status', 'Active')
            ->count();

        if ($currentEnrollees >= $course->student_limit) {
            return redirect()->back()->with('error', 'Sorry, this course has reached its student limit.');
        }

        if ($enrollee) {
            $enrollee->update([
                'status' => 'Active',
                'enrollment_date' => now(),
            ]);
        } else {
            CourseEnrollee::create([
                'course_id' => $course->id,
                'enrollee_id' => $user->id,
                'enrollment_date' => now(),
                'status' => 'Active',
            ]);
        }

        return redirect()->route('learner.course.show', $course)
            ->with('success', 'You have successfully enrolled in this course.');
    }
}

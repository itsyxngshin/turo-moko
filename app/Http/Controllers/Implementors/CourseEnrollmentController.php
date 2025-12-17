<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseEnrollmentController extends Controller
{
    public function join(Course $course)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('redirect', route('course.join', $course->course_code));
        }

        if (auth()->user()->role !== 'learner') {
            abort(403, 'Only learners can enroll in courses.');
        }

        // Optional: prevent duplicate enrollment
        if ($course->learners()->where('user_id', auth()->id())->exists()) {
            return redirect()
                ->route('learner.course.show', $course->course_code)
                ->with('success', 'You are already enrolled in this course.');
        }

        return view('courses.confirm-enrollment', compact('course'));
    }
}


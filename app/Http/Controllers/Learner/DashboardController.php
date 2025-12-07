<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    public function enroll(Course $course)
{
    $learner = auth()->user();

    // Check if already enrolled
    $alreadyEnrolled = CourseEnrollee::where('course_id', $course->id)
        ->where('enrollee_id', $learner->id)
        ->exists();

    if ($alreadyEnrolled) {
        return redirect()->route('learner.course.show', $course)
            ->with('swal', [
                'icon' => 'info',
                'title' => 'Notice',
                'text' => 'You are already enrolled in this course.'
            ]);
    }

    // Check if course is full
    $currentEnrollees = CourseEnrollee::where('course_id', $course)->count();
    if ($currentEnrollees >= $course->student_limit) {
        return back()->with('swal', [
            'icon' => 'error',
            'title' => 'Full Course',
            'text' => 'Sorry, this course is already full.'
        ]);
    }

    // Enroll the student
    CourseEnrollee::create([
        'course_id' => $course->id,
        'enrollee_id' => $learner->id,
        'enrollment_date' => now(),
        'status' => 'Active',
    ]);

    // Redirect to the course show page after enrollment
    return redirect()->route('learner.course.show', $course)
        ->with('swal', [
            'icon' => 'success',
            'title' => 'Enrolled!',
            'text' => 'You have successfully enrolled in the course.'
        ]);
}


public function index()
{
    $learnerId = Auth::id();

    // Featured course: the most recently updated course
    $featuredCourse = Course::orderByDesc('updated_at')->first();

    // Recent course: only the most recently updated course
    $recentCourses = Course::orderByDesc('updated_at')->take(1)->get();

    // Suggested courses: active, visible, and NOT yet enrolled
    $suggestedCourses = Course::where('status', 'Active')
        ->where('visibility', 'Visible')
        ->whereDoesntHave('enrollees', function ($query) use ($learnerId) {
            $query->where('users.id', $learnerId);
        })
        ->take(4)
        ->get();

    return view('livewire.learner.dashboard', [
        'featuredCourse' => $featuredCourse,
        'recentCourses' => $recentCourses,
        'suggestedCourses' => $suggestedCourses,
    ]);
}

}

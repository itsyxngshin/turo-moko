<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Learner Dashboard.
     */
  public function index()
{
    $user = Auth::user();

  
    /* ================= LAST ACCESSED COURSE ================= */
    $lastAccessed = $user->enrolledCourses()
        ->where('courses.status', 'Active')
        ->where('courses.visibility', 'Visible')
        ->wherePivot('status', 'Active') // exclude Dropped
        ->orderByPivot('updated_at', 'desc') // ✅ last interaction
        ->orderByPivot('created_at', 'desc') // fallback: last enrolled
        ->first();

    /* ================= HERO COURSE ================= */
    $heroCourse = $lastAccessed;
    $heroMode = 'resume';

   /* ================= FALLBACK FEATURED COURSE ================= */
if (!$heroCourse) {
    $heroCourse = Course::where('status', 'Active')
        ->where('visibility', 'Visible')
        ->whereDoesntHave('enrollees', function ($q) use ($user) {
            $q->where('enrollee_id', $user->id)
              ->whereIn('status', ['Active', 'Completed']); // ✅ exclude only these
        })
        ->latest()
        ->first();

    $heroMode = 'suggest';
}


    // 4. Suggested courses (active, visible, not dropped, not enrolled)
    $suggestedCourses = Course::where('status', 'Active')
        ->where('visibility', 'Visible')
        ->whereDoesntHave('enrollees', function ($query) use ($user) {
            $query->where('enrollee_id', $user->id)
                  ->where('status', 'Active'); // exclude active enrollee
        })
        ->when($heroCourse, function($query) use ($heroCourse) {
            $query->where('id', '!=', $heroCourse->id);
        })
        ->inRandomOrder()
        ->take(4)
        ->get();

    return view('livewire.learner.dashboard', [
        'heroCourse'       => $heroCourse,
        'heroMode'         => $heroMode,
        'suggestedCourses' => $suggestedCourses,
    ]);
}

    /**
     * Enroll the user in a course.
     */
   public function enroll(Course $course)
{
    $learner = Auth::user();

    // Find existing enrollee record (any status)
    $enrollee = CourseEnrollee::where('course_id', $course->id)
        ->where('enrollee_id', $learner->id)
        ->first();

    // If already ACTIVE → stop
    if ($enrollee && $enrollee->status === 'Active') {
        return redirect()->route('learner.course.show', $course)
            ->with('swal', [
                'icon' => 'info',
                'title' => 'Notice',
                'text' => 'You are already enrolled in this course.'
            ]);
    }

    // Check if course is full (ONLY count ACTIVE enrollees)
    $currentEnrollees = CourseEnrollee::where('course_id', $course->id)
        ->where('status', 'Active')
        ->count();

    if ($currentEnrollees >= $course->student_limit) {
        return back()->with('swal', [
            'icon' => 'error',
            'title' => 'Course Full',
            'text' => 'Sorry, this course has reached its student limit.'
        ]);
    }

    // If enrollee exists but was Dropped → REACTIVATE
    if ($enrollee) {
        $enrollee->update([
            'status' => 'Active',
            'enrollment_date' => now(),
        ]);
    } 
    // Else → NEW enrollment
    else {
        CourseEnrollee::create([
            'course_id' => $course->id,
            'enrollee_id' => $learner->id,
            'enrollment_date' => now(),
            'status' => 'Active',
        ]);
    }

    return redirect()->route('learner.course.show', $course)
        ->with('swal', [
            'icon' => 'success',
            'title' => 'Enrolled!',
            'text' => 'You have successfully enrolled in the course.'
        ]);
}

}
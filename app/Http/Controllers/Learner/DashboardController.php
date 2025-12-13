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

        // 1. Try to find the Last Accessed Course (Priority 1)
        // This looks for courses the user is enrolled in that are Active.
        // It sorts by the pivot table's created_at (most recent enrollment).
        // If you track specific access times, replace 'created_at' with 'last_accessed_at'.
        $lastAccessed = $user->enrolledCourses() 
            ->where('courses.status', 'Active')
            ->where('courses.visibility', 'Visible')
            ->orderByPivot('created_at', 'desc') 
            ->first();

        // 2. Determine the "Hero" Course
        $heroCourse = $lastAccessed;
        $heroMode = 'resume'; // Default mode is "Continue Learning"

        // 3. Fallback: If no valid last course (New user or Course deleted)
        if (!$heroCourse) {
            // Get a featured course (e.g., the latest active one)
            $heroCourse = Course::where('status', 'Active')
                ->where('visibility', 'Visible')
                ->latest()
                ->first();
            
            $heroMode = 'suggest'; // Change mode to "Featured Course"
        }

        // 4. Get Course Suggestions
        // Logic: Active, Visible, and NOT enrolled by the current user.
        // We also exclude the $heroCourse so it doesn't appear twice.
        $suggestedCourses = Course::where('status', 'Active')
            ->where('visibility', 'Visible')
            ->whereDoesntHave('enrollees', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->when($heroCourse, function($query) use ($heroCourse) {
                $query->where('id', '!=', $heroCourse->id);
            })
            ->inRandomOrder() // Randomize suggestions for variety
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
        // Note: We use $course->id here for accuracy
        $currentEnrollees = CourseEnrollee::where('course_id', $course->id)->count();
        
        if ($currentEnrollees >= $course->student_limit) {
            return back()->with('swal', [
                'icon' => 'error',
                'title' => 'Course Full',
                'text' => 'Sorry, this course has reached its student limit.'
            ]);
        }

        // Enroll the student
        CourseEnrollee::create([
            'course_id' => $course->id,
            'enrollee_id' => $learner->id,
            'enrollment_date' => now(),
            'status' => 'Active', // Default status
        ]);

        // Redirect to the course show page after enrollment
        return redirect()->route('learner.course.show', $course)
            ->with('swal', [
                'icon' => 'success',
                'title' => 'Enrolled!',
                'text' => 'You have successfully enrolled in the course.'
            ]);
    }
}
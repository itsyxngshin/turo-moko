<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseEnrollee;
use Illuminate\Http\Request;

class CourseParticipantsController extends Controller
{
    /**
     * Display course participants.
     */

    public $searchName = '';
    public $searchResults = [];


    public function index(Request $request, Course $course)
{
    $implementor = $request->query('implementor'); // pulled from ?implementor=4
    $courseId = $request->query('courseId');       // from ?courseId=19

    $courseId = $course->id;

    $enrollees = CourseEnrollee::where('course_id', $courseId)
        ->with('user')
        ->get();


    return view('livewire.implementors.course-participants', [
        'course'        => $course,
        'course_id'     => $courseId,
        'implementor_id'=> $implementor,
        'enrollees'     => $enrollees
    ]);
}
 public function updatedSearchName()
    {
        $query = $this->searchName;

        if ($query) {
            $this->searchResults = User::where('role_id', 1)
                ->whereHas('profile', function($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                      ->orWhere('middle_name', 'like', "%{$query}%")
                      ->orWhere('last_name', 'like', "%{$query}%");
                })
                ->orWhere('username', 'like', "%{$query}%")
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addEnrollee($userId)
    {
        // Prevent duplicate enrollment
        $exists = CourseEnrollee::where('course_id', $this->courseId)
            ->where('enrollee_id', $userId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'User is already enrolled.');
            return;
        }

        CourseEnrollee::create([
            'course_id' => $this->courseId,
            'enrollee_id' => $userId,
            'enrollment_date' => now(),
            'status' => 'Active'
        ]);

        session()->flash('success', 'Enrollee added successfully.');
        $this->searchName = '';
        $this->searchResults = [];
    }

    public function render()
    {
        // Load current enrollees
        $enrollees = CourseEnrollee::where('course_id', $this->courseId)
            ->with('user.profile')
            ->get();

        return view('livewire.implementors.course-participants', [
            'enrollees' => $enrollees
        ]);
    }
}
<?php

namespace App\Livewire\Implementors;

use Livewire\Component;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseEnrollee;

class CourseParticipants extends Component
{
    public Course $course;       // Bound course
    public $searchName = '';     // Search input
    public $searchResults = [];  // Live search results

    public function mount(Course $course)
    {
        $this->course = $course;  // Route model binding
    }



    public function removeEnrollee($enrolleeId)
    {
        $enrollee = CourseEnrollee::where('course_id', $this->course->id)
            ->where('enrollee_id', $enrolleeId)->first();

        if (!$enrollee) {
            session()->flash('error', 'Enrollee not found.');
            return;
        }

        $enrollee->delete();
        session()->flash('success', 'Enrollee removed successfully.');
    }

    public function render()
    {
        $enrollees = CourseEnrollee::where('course_id', $this->course->id)
            ->with('user.profile')
            ->get();

        return view('livewire.implementors.course-participants', [
            'enrollees' => $enrollees
        ])->extends('layouts.layout')
          ->section('content');
    }
}

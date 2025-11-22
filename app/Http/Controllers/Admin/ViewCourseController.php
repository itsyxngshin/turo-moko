<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class ViewCourseController extends Controller
{
    /**
     * Show the course information page.
     *
     * @param string $courseCode
     * @return \Illuminate\View\View
     */
   public function show($courseCode)
{
    $course = Course::where('course_code', $courseCode)
        ->with([
            'activeCoverPhoto',
            'enrollees',
            'lessons.modules',  // load modules through lessons
            'assignments',
            'evaluations',
            'announcements',
        ])
        ->firstOrFail();

    return view('livewire.admin.view-course', [
        'course' => $course,
        'lessons' => $course->lessons,
        'assignments' => $course->assignments,
        'evaluations' => $course->evaluations,
        'announcements' => $course->announcements,
        'enrollees' => $course->enrollees,
    ]);
}

}

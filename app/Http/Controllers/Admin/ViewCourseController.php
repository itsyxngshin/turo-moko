<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ViewCourseController extends Controller
{
    public function show($id)
{
    $course = Course::with(['enrollees', 'modules', 'assignments', 'evaluations', 'announcements'])->findOrFail($id);
    return view('courses.view', [
        'course' => $course,
        'modules' => $course->modules,
        'assignments' => $course->assignments,
        'evaluations' => $course->evaluations,
        'announcements' => $course->announcements,
    ]);
}

}

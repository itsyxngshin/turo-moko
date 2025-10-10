<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\Assignment;
use App\Models\Resource;
use App\Models\User;
use App\Models\ProgramEvaluation;
use App\Models\Quiz;
use App\Models\Announcement;

class ImplementorCourseInformationController extends Controller
{
    
    public function show($courseId)
{
    // Get the logged-in implementor (for now still hardcoded as ID 2)
    $implementor = User::where('id', 2)->where('role_id', 2)->first();

    // Fetch the course only if it belongs to this implementor
    $course = Course::where('id', $courseId)
        ->where('implementer_id', $implementor->id)
        ->firstOrFail();

    // Fetch related modules, assignments, etc.
    $courseIds = Course::where('implementer_id', $implementor->id)
        ->pluck('id');

    $announcements = Announcement::whereIn('course_id', $courseIds)
        ->orderBy('created_at', 'desc')
        ->get();

    $modules = Module::where('lesson_id', $course->id)->get();
    $assignments = Assignment::where('lesson_id', $course->id)->get();
    $evaluations = ProgramEvaluation::where('course_id', $course->id)->get();
    $quiz = Quiz::where('course_id', $course->id)->get();

    // Always pass both course and courseId to the Blade
    return view('livewire.implementors.implementor-course-details', [
        'course'       => $course,
        'courseId'     => $course->id,
        'modules'      => $modules,
        'assignments'  => $assignments,
        'evaluations'  => $evaluations,
        'quiz'         => $quiz,
        'announcements'=> $announcements,
    ]);
}

}

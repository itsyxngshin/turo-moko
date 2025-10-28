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
        // Get the logged-in implementor (replace hardcoded 2 with auth()->id() later)
        $implementor = User::where('id', 2)
            ->where('role_id', 2)
            ->firstOrFail();

        // Fetch the specific course belonging to this implementor
        $course = Course::where('id', $courseId)
            ->where('implementer_id', $implementor->id)
            ->firstOrFail();

        // ✅ Fetch only announcements, modules, assignments, etc. related to this course
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $modules = Module::where('lesson_id', $course->id)->get();
        $assignments = Assignment::where('lesson_id', $course->id)->get();
        $evaluations = ProgramEvaluation::where('course_id', $course->id)->get();
        $quiz = Quiz::where('course_id', $course->id)->get();

        // ✅ Return everything to the Blade
        return view('livewire.implementors.implementor-course-details', [
            'course'        => $course,
            'courseId'      => $course->id,
            'modules'       => $modules,
            'assignments'   => $assignments,
            'evaluations'   => $evaluations,
            'quiz'          => $quiz,
            'announcements' => $announcements,
        ]);
    }
}

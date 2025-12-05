<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
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
    public function show(Course $course)
    {
        // Get logged-in user
        $implementor = auth()->user();

        // Ensure the user is an implementor
        if (!$implementor || $implementor->role_id != 2) {
            abort(403, 'Unauthorized: Only implementors can access this page.');
        }

        // Ensure the implementor OWNS this course
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized: You do not own this course.');
        }

        // Fetch related data
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $evaluations = ProgramEvaluation::where('course_id', $course->id)->get();
        $quiz = Quiz::where('course_id', $course->id)->get();

        // Fetch modules
        $modules = Module::where('course_id', $course->id)
            ->orderBy('module_number', 'asc')
            ->with('lessons')
            ->get();

        return view('livewire.implementors.implementor-course-details', [
            'course'        => $course,
            'courseId'      => $course->id,
            'modules'       => $modules,
            'evaluations'   => $evaluations,
            'quiz'          => $quiz,
            'announcements' => $announcements,
        ]);
    }

    public function destroy(Module $module)
    {
        $module->delete();

        return redirect()->back()->with('success', 'Module deleted successfully.');
    }
}

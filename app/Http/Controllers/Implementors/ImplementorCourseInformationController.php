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
        

        // Get the logged-in implementor (temporary hardcoded ID = 4)
        $implementor = User::where('id', 4)
            ->where('role_id', 2)
            ->firstOrFail();

        // Ensure this course belongs to the implementor
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'Unauthorized access to this course.');
        }

        // Fetch related data
        $announcements = Announcement::where('course_id', $course->id)
            ->orderBy('created_at', 'desc')
            ->get();

       // $modules = Module::where('lesson_id', $course->id)->get();
       // $assignments = Assignment::where('lesson_id', $course->id)->get();
        $evaluations = ProgramEvaluation::where('course_id', $course->id)->get();
        $quiz = Quiz::where('course_id', $course->id)->get();

        // Fetch modules for this course
        $module = Module::where('course_id', $course->id)
            ->orderBy('module_number', 'asc')
            ->with('lessons') // hasOne relation
            ->get();


    return view('livewire.implementors.implementor-course-details', [
            'course'        => $course,
            'courseId'      => $course->id,
            'modules'       => $module,
            //'assignments'   => $assignments,
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

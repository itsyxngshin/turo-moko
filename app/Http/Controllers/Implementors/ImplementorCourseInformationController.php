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
use App\Models\Engagement;

class ImplementorCourseInformationController extends Controller
{
    
    public function show($courseId)
    {
        // Get the logged-in implementor
        //$implementor = auth()->user();

         $implementor = User::where('id', 2)->where('role_id', 2)->first();

       // if (!$implementor) {
        //    return redirect()->route('login')->with('error', 'Please log in.');
        //}

        // Fetch the course only if it belongs to this implementor
        $course = Course::where('id', $courseId)         // $courseId = 5
                ->where('implementer_id', $implementor->id) // $implementor->id = 2
                ->firstOrFail();


        // Fetch related modules, assignments, and resources
       // Assuming courses table has implementor_id
$courseIds = Course::where('implementer_id', auth()->id())
    ->pluck('id');

$announcements = Engagement::whereIn('course_id', $courseIds)
    ->orderBy('created_at', 'desc')
    ->get();



        $modules = Module::where('lesson_id', $course->id)->get();
        $assignments = Assignment::where('lesson_id', $course->id)->get();
        $evaluations = ProgramEvaluation::where('course_id', $course->id)->get();
        $quiz = Quiz::where('course_id', $course->id)->get();
//$resources = Resource::where('course_id', $course->id)->get();

       return view('livewire.implementors.implementor-course-details', compact(
    'course', 'modules', 'assignments', 'evaluations', 'quiz', 'announcements',
));

    }
}

<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\CourseFeedback;
use App\Models\Module;
use App\Models\ProgramEvaluation;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\SectionHeader;
use App\Models\Submission;
use Illuminate\Support\Collection;

class CourseController extends Controller
{
    
public function index()
{
    $userId = Auth::id();

    // ✅ Active courses: enrolled + not completed
    $activeCourses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId);
        })
        ->where('progress', '<', 100)
        ->count();

    // ✅ Completed courses: enrolled + completed
   $completedCourses = CourseEnrollee::where('enrollee_id', $userId)
    ->where('status', 'completed')
    ->count();

    // ✅ Fetch only courses the learner is enrolled in
    $courses = Course::whereHas('enrollees', function ($q) use ($userId) {
            $q->where('course_enrollees.enrollee_id', $userId);
        })
        ->orderBy('course_title')
        ->get();

    // Optional placeholders
    $pendingActivities = 5;
    $pendingEvaluations = 2;

    $featuredCourse = $courses->first();

    return view('learner.classes', compact(
        'courses',
        'activeCourses',
        'completedCourses',
        'pendingActivities',
        'pendingEvaluations',
        'featuredCourse'
    ));
}


public function leaveCourse($courseId)
{
    $learnerId = auth()->id();

    $enrollee = CourseEnrollee::where('course_id', $courseId)
        ->where('enrollee_id', $learnerId)
        ->first();

    if ($enrollee) {
        $enrollee->status = 'Dropped';
        $enrollee->save();
    }

    session()->flash('success', 'You have successfully left the course.');
    return redirect()->route('learner.classes');
}



public function show(Course $course)
{
    $learner = auth()->user();

    if (!$learner || (int) $learner->role_id !== 1) {
        abort(403, 'Unauthorized. You must be a learner.');
    }

    $isEnrolled = $course->enrollees()
        ->where('users.id', $learner->id)
        ->exists();

    if (!$isEnrolled) {
        abort(403, 'You are not enrolled in this course.');
    }

    // Modules ordered by order column
    $modules = Module::where('course_id', $course->id)
    ->where('visibility', 'Visible')
    ->orderBy('order', 'asc')
    ->with('lessons')
    ->get();


    // Quizzes ordered by order column
    $quizzes = Quiz::where('course_id', $course->id)
        ->where('status', 'Published')
        ->where('visibility', true)
        ->withCount('results')
        ->orderBy('order', 'asc')
        ->get();

    $enrolleeRecord = CourseEnrollee::where('course_id', $course->id)
        ->where('enrollee_id', $learner->id)
        ->first();

    if ($enrolleeRecord && $quizzes->isNotEmpty()) {
        $completedQuizIds = QuizResult::where('course_enrollee_id', $enrolleeRecord->id)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->pluck('quiz_id')
            ->all();

        $quizzes = $quizzes->map(function ($quiz) use ($completedQuizIds) {
            $quiz->learner_status = in_array($quiz->id, $completedQuizIds, true)
                ? 'Completed'
                : 'Available';
            return $quiz;
        });
    } else {
        $quizzes = $quizzes->map(function ($quiz) {
            $quiz->learner_status = 'Available';
            return $quiz;
        });
    }

    // Assignments ordered by order column
    $assignments = Assignment::where('course_id', $course->id)
        ->orderBy('order', 'asc')
        ->get();

    if ($enrolleeRecord) {
        $assignments = $assignments->map(function ($assignment) use ($enrolleeRecord) {
            $hasSubmission = Submission::where('assignment_id', $assignment->id)
                ->where('enrollee_id', $enrolleeRecord->id)
                ->exists();

            $assignment->learner_status = $hasSubmission ? 'Completed' : $assignment->status;
            return $assignment;
        });
    } else {
        $assignments = $assignments->map(function ($assignment) {
            $assignment->learner_status = $assignment->status;
            return $assignment;
        });
    }

    // Evaluations ordered by order column
    $evaluations = ProgramEvaluation::where('course_id', $course->id)
        ->orderBy('order', 'asc')
        ->get()
        ->map(function ($evaluation) use ($learner) {
            $evaluation->due_date = $evaluation->created_at
                ? $evaluation->created_at->copy()->addDays(7)
                : null;

            $evaluation->learner_completed = CourseFeedback::where('course_id', $evaluation->course_id)
                ->where('learner_id', $learner->id)
                ->exists();

            $evaluation->learner_status = $evaluation->learner_completed ? 'Completed' : 'Available';
            return $evaluation;
        });

    // Announcements ordered by order column
    $announcements = Announcement::where('course_id', $course->id)
        ->orderBy('order', 'asc')
        ->get();

    // Section Headers ordered by order column
    $sectionHeaders = SectionHeader::where('course_id', $course->id)
        ->orderBy('order', 'asc')
        ->get();



$timeline = collect();

/* MODULES */
foreach ($modules as $module) {
    $timeline->push([
        'type' => 'module',
        'order' => $module->order,
        'data' => $module,
    ]);
}

/* ASSIGNMENTS */
foreach ($assignments as $assignment) {
    $timeline->push([
        'type' => 'assignment',
        'order' => $assignment->order,
        'data' => $assignment,
    ]);
}

/* QUIZZES */
foreach ($quizzes as $quiz) {
    $timeline->push([
        'type' => 'quiz',
        'order' => $quiz->order,
        'data' => $quiz,
    ]);
}

/* EVALUATIONS */
foreach ($evaluations as $evaluation) {
    $timeline->push([
        'type' => 'evaluation',
        'order' => $evaluation->order,
        'data' => $evaluation,
    ]);
}

/* ANNOUNCEMENTS */
foreach ($announcements as $announcement) {
    $timeline->push([
        'type' => 'announcement',
        'order' => $announcement->order,
        'data' => $announcement,
    ]);
}

/* SECTION HEADERS */
foreach ($sectionHeaders as $section) {
    $timeline->push([
        'type' => 'section_header',
        'order' => $section->order,
        'data' => $section,
    ]);
}

/* ORDER BY order COLUMN */
$timeline = $timeline->sortBy('order')->values();


  return view('livewire.learner.course-information', [
    'course' => $course,
    'modules' => $modules,
    'quiz' => $quizzes,
    'assignments' => $assignments,
    'evaluations' => $evaluations,
    'announcements' => $announcements,
    'sectionHeaders' => $sectionHeaders,
    'timeline' => $timeline,
]);


}



}

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
use App\Models\Submission;

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
        ->orderBy('title')
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

    // Modules ordered by module_number (oldest to newest)
    $modules = Module::where('course_id', $course->id)
    ->where('visibility', 'Visible')   // ✅ Only modules with Visible visibility
    ->orderBy('module_number', 'asc')
    ->with('lessons')
    ->get();


    // Quizzes ordered by creation date ascending (oldest first)
    $quizzes = Quiz::where('course_id', $course->id)
        ->where('status', 'Published')
        ->where('visibility', true)
        ->withCount('results')
        ->orderBy('created_at', 'asc')
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

    // Assignments ordered by creation date ascending (oldest first)
    $assignments = Assignment::where('course_id', $course->id)
        ->orderBy('created_at', 'asc')
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

    // Evaluations ordered by creation date ascending (oldest first)
    $evaluations = ProgramEvaluation::where('course_id', $course->id)
        ->orderBy('created_at', 'asc')
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

    // Announcements ordered by creation date ascending (oldest first)
    $announcements = Announcement::where('course_id', $course->id)
        ->orderBy('created_at', 'asc')
        ->get();

    return view('livewire.learner.course-information', [
        'course' => $course,
        'modules' => $modules,
        'quiz' => $quizzes,
        'assignments' => $assignments,
        'evaluations' => $evaluations,
        'announcements' => $announcements,
    ]);
}



}

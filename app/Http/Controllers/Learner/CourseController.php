<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
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
        // All courses
        $courses = Course::orderBy('title')->get();

        // Counts for top stats
        $activeCourses = Course::where('progress', '<', 100)->count();
        $completedCourses = Course::where('progress', 100)->count();

        // Temporary placeholders until real logic exists
        $pendingActivities = 5;
        $pendingEvaluations = 2;

        // Pick a featured course (first one for now)
        $featuredCourse = Course::first();

        return view('learner.classes', compact(
            'courses',
            'activeCourses',
            'completedCourses',
            'pendingActivities',
            'pendingEvaluations',
            'featuredCourse'
        ));
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
        ->orderBy('module_number', 'asc')
        ->with('lessons')
        ->get();

    // Quizzes ordered by creation date ascending (oldest first)
    $quizzes = Quiz::where('course_id', $course->id)
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

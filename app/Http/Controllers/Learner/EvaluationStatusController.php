<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollee;
use App\Models\ProgramEvaluation;
use Illuminate\Support\Facades\Auth;

class EvaluationStatusController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('auth.login');
        }

        // Get user's enrolled courses
        $enrolledCourses = $user->enrolledCourses()
            ->where('courses.status', 'active')
            ->wherePivot('status', 'active')
            ->get();

        $courseIds = $enrolledCourses->pluck('id');
        
        // Get enrollee records for this user
        $enrolleeRecords = CourseEnrollee::where('enrollee_id', $user->id)
            ->whereIn('course_id', $courseIds)
            ->get()
            ->keyBy('course_id');

        $pendingEvaluations = collect();
        $completedEvaluations = collect();

        foreach ($enrolledCourses as $course) {
            $enrollee = $enrolleeRecords->get($course->id);
            
            if (!$enrollee) continue;

            // Check if evaluation exists and is submitted
            $evaluation = ProgramEvaluation::where('course_id', $course->id)
                ->where('enrollee_id', $enrollee->id)
                ->first();

            $evalData = (object) [
                'id' => $evaluation?->id ?? null,
                'course_id' => $course->id,
                'title' => $course->course_title . ' - Course Evaluation',
                'course_name' => $course->course_title,
                'due_date' => $evaluation?->created_at?->addDays(7) ?? now()->addDays(7),
                'completed_at' => $evaluation?->submitted_at,
                'implementer_id' => $course->implementer_id,
            ];

            if ($evaluation && $evaluation->submitted_at) {
                $completedEvaluations->push($evalData);
            } else {
                $pendingEvaluations->push($evalData);
            }
        }

        return view('livewire.learner.evaluation-status', compact(
            'pendingEvaluations',
            'completedEvaluations'
        ));
    }
}

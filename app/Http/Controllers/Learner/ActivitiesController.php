<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\CourseEnrollee;

class ActivitiesController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled course IDs
        $enrolleeIds = CourseEnrollee::where('enrollee_id', $user->id)
            ->where('status', 'Active')
            ->pluck('id');
        
        $courseIds = CourseEnrollee::where('enrollee_id', $user->id)
            ->where('status', 'Active')
            ->pluck('course_id');
        
        // Get pending assignments (open, not submitted)
        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->where('status', 'Open')
            ->whereDoesntHave('submissions', function($q) use ($enrolleeIds) {
                $q->whereIn('enrollee_id', $enrolleeIds);
            })
            ->with('course')
            ->get()
            ->map(function($assignment) {
                return [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'description' => $assignment->instruction ?? 'No description',
                    'type' => 'assignment',
                    'course_name' => $assignment->course->course_title ?? 'N/A',
                    'course_code' => $assignment->course->course_code ?? null,
                    'due_date' => $assignment->end_date,
                ];
            });
        
        // Sort by due date
        $activities = $assignments->sortBy('due_date')->values();

        return view('learner.activities', compact('activities'));
    }
}

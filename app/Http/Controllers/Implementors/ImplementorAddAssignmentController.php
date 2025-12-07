<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;

class ImplementorAddAssignmentController extends Controller
{
    public function create(Course $course)
    {
        // Use authenticated user
        $implementor = auth()->user();
        
        // Check if user is logged in and is an implementor
        if (!$implementor || $implementor->role_id !== 2) {
            abort(403, 'Unauthorized. You must be an implementor.');
        }

        // Verify the course belongs to the implementor
        if ($course->implementer_id !== $implementor->id) {
            abort(403, 'This course does not belong to you.');
        }

        return view('livewire.implementors.add-assignment', [
            'course' => $course,
            'implementor' => $implementor
        ]);
    }

    public function store(Request $request, $courseId)
    {
        $request->validate([
            'assignment_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Assignment::create([
            'course_id' => $courseId,
            'name' => $request->assignment_name,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('implementors.course-info', $courseId)
            ->with('success', 'Assignment added successfully.');
    }
}

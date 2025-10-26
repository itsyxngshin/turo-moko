<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;

class ImplementorAddAssignmentController extends Controller
{
    public function create($courseId)
    {
        $implementor = User::where('id', 2)->where('role_id', 2)->first();

        $course = Course::where('id', $courseId)
        ->where('implementer_id', $implementor->id)
        ->firstOrFail();



        return view('livewire.implementors.add-assignment', compact('course'));
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

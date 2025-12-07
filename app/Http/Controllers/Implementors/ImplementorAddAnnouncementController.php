<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImplementorAddAnnouncementController extends Controller
{
      public function show($courseId = null)
    {
        // Get authenticated implementor
        $implementor = auth()->user();
        
        if (!$implementor || $implementor->role_id !== 2) {
            abort(403, 'Unauthorized. You must be an implementor.');
        }

        $course = null;
        if ($courseId) {
            $course = Course::where('id', $courseId)
                ->where('implementer_id', $implementor->id)
                ->firstOrFail();
        }

        return view('livewire.implementors.add-announcement', [
            'course' => $course,
            'implementor' => $implementor
        ]);
    }
}

<?php

namespace App\Http\Controllers\Implementors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImplementorAddAnnouncementController extends Controller
{
      public function show($courseId)
    {
        // Example: hardcoded instructor ID = 2
        $this->instructor = User::where('id', 2)->where('role_id', 2)->first();

        $course = Course::where('id', $courseId)
        ->where('implementer_id', $implementor->id)
        ->firstOrFail();
    }
}

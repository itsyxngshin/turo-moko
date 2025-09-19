<?php

namespace App\Http\Controllers\Learner;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function show($id)
    {
        $activity = Activity::findOrFail($id);

        return view('learner.activity', compact('activity'));
    }
}
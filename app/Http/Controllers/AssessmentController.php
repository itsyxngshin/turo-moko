<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    /**
     * Store a newly created assessment
     */
    public function store(Request $request)
    {
        // For now, just dump all the data to inspect
        dd($request->all());
        
        // Future implementation will:
        // 1. Validate the request data
        // 2. Create the assessment record
        // 3. Create assessment items records
        // 4. Return appropriate response
    }
}


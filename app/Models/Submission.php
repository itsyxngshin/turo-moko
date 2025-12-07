<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'enrollee_id', 'assignment_id', 'instruction',
        'status', 'attachment', 'attachment_original_name', 'start_date',
        'deadline', 'order', 'visibility', 'post_date', 'edit_date'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class);
    }
}

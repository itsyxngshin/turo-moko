<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramEvaluation extends Model
{
    protected $fillable = ['course_id', 'enrollee_id', 'description', 'status', 'submitted_at', 'due_date'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'due_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function ratingResponses()
    {
        return $this->hasMany(RatingResponse::class, 'prog_evaluation_id');
    }

    public function commentResponses()
    {
        return $this->hasMany(CommentResponse::class, 'prog_evaluation_id');
    }
}

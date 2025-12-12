<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramEvaluation extends Model
{
    protected $fillable = ['course_id', 'description', 'status'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function ratings()
    {
        return $this->hasMany(RatingResponse::class, 'program_eval_id');
    }

    public function comments()
    {
        return $this->hasMany(CommentResponse::class, 'program_eval_id');
    }

    public function enrollees()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    
}

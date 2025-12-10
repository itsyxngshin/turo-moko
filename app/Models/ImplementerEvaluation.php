<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerEvaluation extends Model
{
   protected $fillable = [
        'course_id', 'implementer_id', 'enrollee_id', 'description', 'status', 'submitted_at', 'due_date'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'due_date' => 'date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function implementer()
    {
        return $this->belongsTo(User::class, 'implementer_id');
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function ratingResponses()
    {
        return $this->hasMany(ImplementerRatingResponse::class, 'imp_eval_id');
    }

    public function commentResponses()
    {
        return $this->hasMany(ImplementerCommentResponse::class, 'imp_eval_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'quiz_id',
        'course_enrollee_id',
        'score',
        'remarks',
        'status',
        'checked_at'
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'score' => 'decimal:2'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'course_enrollee_id');
    }

    /**
     * Get all answers for this quiz result
     */
    public function answers()
    {
        return Answer::where('quiz_id', $this->quiz_id)
            ->where('course_enrollee_id', $this->course_enrollee_id)
            ->get();
    }
}

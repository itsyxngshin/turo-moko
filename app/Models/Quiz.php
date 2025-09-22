<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'course_id', 'quiz_title', 'description',
        'status', 'start_date', 'end_date', 'timer_hours', 'timer_minutes', 'submission_limit', 'visibility'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }
}

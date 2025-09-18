<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
     protected $fillable = [
        'course_enrollee_id', 'question_id', 'answer_text', 'points', 'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class);
    }
}

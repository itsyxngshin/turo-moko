<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'question_id',
        'quiz_id',
        'course_enrollee_id',
        'answer_text',
        'option_id',
        'points',
        'is_correct'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'course_enrollee_id');
    }

    public function option()
    {
        return $this->belongsTo(Choice::class, 'option_id');
    }
}

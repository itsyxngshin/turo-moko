<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollee extends Model
{
     protected $fillable = [
        'user_id', 'course_id', 'enrollment_date', 'completion_date', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function implementerEvaluations()
    {
        return $this->hasMany(ImplementerEvaluation::class, 'enrollee_id');
    }

    public function programEvaluations()
    {
        return $this->hasMany(ProgramEvaluation::class, 'enrollee_id');
    }
}

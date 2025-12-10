<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentResponse extends Model
{
    protected $fillable = ['enrollee_id', 'prog_comment_id', 'prog_evaluation_id', 'comment'];

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function comment()
    {
        return $this->belongsTo(ProgramComment::class, 'prog_comment_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(ProgramEvaluation::class, 'prog_evaluation_id');
    }
}

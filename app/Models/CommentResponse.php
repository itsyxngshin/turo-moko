<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentResponse extends Model
{
    protected $fillable = ['enrollee_id', 'feedback', 'rating_value'];

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(ProgramComment::class, 'prog_comment_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerCommentResponse extends Model
{
    protected $fillable = [
        'enrollee_id', 'prog_rating_id', 'imp_comment_id', 'comment'
    ];

    public function comment()
    {
        return $this->belongsTo(ImplementerComment::class, 'imp_comment_id');
    }

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class);
    }
}

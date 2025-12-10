<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerCommentResponse extends Model
{
    protected $fillable = [
        'enrollee_id', 'imp_eval_id', 'imp_comment_id', 'comment'
    ];

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function comment()
    {
        return $this->belongsTo(ImplementerComment::class, 'imp_comment_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(ImplementerEvaluation::class, 'imp_eval_id');
    }
}

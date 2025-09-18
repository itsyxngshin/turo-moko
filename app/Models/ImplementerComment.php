<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerComment extends Model
{
     protected $fillable = ['imp_eval_id', 'description', 'status'];

    public function evaluation()
    {
        return $this->belongsTo(ImplementerEvaluation::class, 'imp_eval_id');
    }

    public function responses()
    {
        return $this->hasMany(ImplementerCommentResponse::class, 'imp_comment_id');
    }

    public function response()
    {
        return $this->hasMany(ImplementerCommentResponse::class, 'imp_comment_id');
    }
}

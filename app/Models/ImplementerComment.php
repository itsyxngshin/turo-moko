<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerComment extends Model
{
    protected $fillable = ['description', 'status', 'order'];

    public function responses()
    {
        return $this->hasMany(ImplementerCommentResponse::class, 'imp_comment_id');
    }
}

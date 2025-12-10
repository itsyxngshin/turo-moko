<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramComment extends Model
{
    protected $fillable = ['description', 'status', 'order'];

    public function responses()
    {
        return $this->hasMany(CommentResponse::class, 'prog_comment_id');
    }
}

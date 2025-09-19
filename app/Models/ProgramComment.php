<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramComment extends Model
{
    protected $fillable = ['description', 'status'];

    public function responses()
    {
        return $this->hasMany(CommentResponse::class, 'program_comment_id');
    }
    
}

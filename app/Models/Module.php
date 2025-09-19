<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'lesson_id', 'module_title', 'discussion', 'attachments'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}

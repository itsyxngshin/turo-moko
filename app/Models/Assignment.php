<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = [
        'lesson_id', 'title', 'instruction', 'status',
        'start_date', 'end_date', 'deadline', 'order',
        'filetype_allowed', 'visibility', 'post_date'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}

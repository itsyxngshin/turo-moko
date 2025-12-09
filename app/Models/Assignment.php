<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory; // ← Add this

    protected $fillable = [
        'course_id',
        'lesson_id',
        'title',
        'instruction',
        'attachment',
        'attachment_original_name',
        'status',
        'start_date',
        'end_date',
        'filetype_allowed',
        'text_allowed',
        'max_file_size',
        'order',
        'visibility',
        'post_date',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}

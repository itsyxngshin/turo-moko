<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'course_id', 'module_title', 'module_number'
    ];

    public function course()
{
    return $this->belongsTo(Course::class);
}

    public function lessons()
{
    return $this->hasOne(Lesson::class, 'module_id');
}
}

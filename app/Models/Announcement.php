<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'body',
        'created_by',
    ];

    /**
     * Relationships
     */

    // Announcement belongs to a course
    public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}

    // Announcement was created by a user
    public function user()
    {
        return $this->belongsTo(User::class, 'id');
    }
}

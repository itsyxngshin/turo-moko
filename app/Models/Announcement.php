<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'user_id',
        'title',
        'content',
        'order',
    ];

    /**
     * Relationships
     */

    // Announcement belongs to a course
    public function course()
{
    return $this->belongsTo(Course::class, 'course_id');
}

public function attachments()
    {
        return $this->hasMany(AnnouncementAttachment::class);
    }

    // Announcement was created by a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

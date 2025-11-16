<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'implementer_id', 'organization_id', 'category_id', 'course_code',
        'name', 'background', 'status', 'visibility',
        'start_date', 'end_date'
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($course) {
        $course->course_code = self::generateUniqueCode();
    });
}

protected static function generateUniqueCode()
{
    do {
        $code = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
    } while (self::where('course_code', $code)->exists());

    return $code;
}

public function getRouteKeyName()
{
    return 'course_code';
}

     public function users()
    {
        return $this->belongsToMany(User::class, 'course_enrollees', 'course_id', 'user_id')
            ->withTimestamps();
    }
    
    public function implementer()
    {
        return $this->belongsTo(User::class, 'implementer_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function enrollees()
    {
        return $this->hasMany(CourseEnrollee::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function tags()
    {
        return $this->hasMany(CourseTag::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
     // 👇 Add this relationship
    public function coverPhotos()
    {
        return $this->hasMany(CoverPhoto::class, 'course_id');
    }

    public function engagements()
{
    return $this->hasMany(Engagement::class, 'course_id');
}

 public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
    
    public function activeCoverPhoto()
    {
        return $this->hasOne(CoverPhoto::class)->where('status', 'Active');
    }

}

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
        'start_date', 'end_date', 'student_limit'
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

    public function evaluations()
{
    return $this->hasMany(ImplementerEvaluation::class, 'course_id');
}


    /**
     * --------------------------------------
     * ENROLLEES (Correct Pivot Relationship)
     * --------------------------------------
     */
    public function enrollees()
    {
        return $this->belongsToMany(
            User::class,
            'course_enrollees',
            'course_id',
            'enrollee_id' // correct column name from your pivot table
        )
        ->withPivot('enrollment_date', 'completion_date', 'status')
        ->where('role_id', 1); // only learners (role_id = 1)
    }

    /**
     * If you ever need the raw pivot model:
     */
    public function enrolleeRecords()
    {
        return $this->hasMany(CourseEnrollee::class, 'course_id');
    }

    /**
     * --------------------------------------
     * COURSE CREATOR / ORGANIZATION
     * --------------------------------------
     */
    public function implementer()
    {
        return $this->belongsTo(User::class, 'implementer_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * --------------------------------------
     * LESSONS (Correct)
     * --------------------------------------
     */
    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id');
    }

    /**
     * --------------------------------------
     * ASSIGNMENTS (Correct via lessons)
     * Course → Lesson → Assignment
     * --------------------------------------
     */
    public function assignments()
    {
        return $this->hasManyThrough(
            Assignment::class, // final model
            Lesson::class,     // intermediate model
            'course_id',       // FK on lessons table
            'lesson_id',       // FK on assignments table
            'id',              // PK on courses
            'id'               // PK on lessons
        );
    }

    /**
     * --------------------------------------
     * QUIZZES / TAGS / CATEGORY
     * --------------------------------------
     */
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

    /**
     * --------------------------------------
     * COVER PHOTOS
     * --------------------------------------
     */
    public function coverPhotos()
    {
        return $this->hasMany(CoverPhoto::class, 'course_id');
    }

    public function activeCoverPhoto()
    {
        return $this->hasOne(CoverPhoto::class)->where('status', 'Active');
    }

    /**
     * --------------------------------------
     * ANNOUNCEMENTS
     * --------------------------------------
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'course_id');
    }

    /**
     * --------------------------------------
     * ENGAGEMENTS
     * --------------------------------------
     */
    public function engagements()
    {
        return $this->hasMany(Engagement::class, 'course_id');
    }
}

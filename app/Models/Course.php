<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'implementer_id', 'organization_id', 'category_id',
        'name', 'subject', 'background', 'status', 'visibility',
        'start_date', 'end_date'
    ];

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
}

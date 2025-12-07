<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeedback extends Model
{
    use HasFactory;

    protected $table = 'course_feedbacks';

    protected $fillable = [
        'course_id',
        'learner_id',
        'overall_rating',
        'materials_rating',
        'structure_rating',
        'engagement_rating',
        'comment',
    ];

    // Relationships
    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
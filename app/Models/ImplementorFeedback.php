<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImplementorFeedback extends Model
{
    use HasFactory;

    protected $table = 'implementor_feedbacks'; // Add this line!

    protected $fillable = [
        'course_id',
        'learner_id',
        'implementer_id',
        'teaching_effectiveness_rating',
        'responsiveness_rating',
        'explanation_clarity_rating',
        'recommendation_rating',
        'comment',
    ];

    // Relationships
    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function implementer()
    {
        return $this->belongsTo(User::class, 'implementer_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
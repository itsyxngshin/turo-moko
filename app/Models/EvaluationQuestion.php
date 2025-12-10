<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    protected $fillable = ['type', 'text', 'order', 'status'];

    protected $casts = [
        'order' => 'integer',
    ];

    // Relationships
    public function ratingResponses()
    {
        return $this->hasMany(RatingResponse::class, 'evaluation_question_id')
            ->whereIn('type', ['program_rating', 'implementer_rating']);
    }

    public function commentResponses()
    {
        return $this->hasMany(CommentResponse::class, 'evaluation_question_id')
            ->whereIn('type', ['program_comment', 'implementer_comment']);
    }

    // Scopes
    public function scopeProgramRatings($query)
    {
        return $query->where('type', 'program_rating')->orderBy('order');
    }

    public function scopeProgramComments($query)
    {
        return $query->where('type', 'program_comment')->orderBy('order');
    }

    public function scopeImplementerRatings($query)
    {
        return $query->where('type', 'implementer_rating')->orderBy('order');
    }

    public function scopeImplementerComments($query)
    {
        return $query->where('type', 'implementer_comment')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}

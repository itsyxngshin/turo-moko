<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingResponse extends Model
{
    protected $fillable = ['enrollee_id', 'prog_rating_id', 'prog_evaluation_id', 'rating_value'];

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function rating()
    {
        return $this->belongsTo(ProgramRating::class, 'prog_rating_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(ProgramEvaluation::class, 'prog_evaluation_id');
    }
}

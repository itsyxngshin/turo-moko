<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerRatingResponse extends Model
{
    protected $fillable = ['enrollee_id', 'imp_eval_id', 'imp_rating_id', 'rating_value'];

    public function enrollee()
    {
        return $this->belongsTo(CourseEnrollee::class, 'enrollee_id');
    }

    public function rating()
    {
        return $this->belongsTo(ImplementerRating::class, 'imp_rating_id');
    }

    public function evaluation()
    {
        return $this->belongsTo(ImplementerEvaluation::class, 'imp_eval_id');
    }
}

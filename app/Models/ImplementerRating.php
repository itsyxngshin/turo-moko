<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerRating extends Model
{
    protected $fillable = ['imp_eval_id', 'statement'];

    public function evaluation()
    {
        return $this->belongsTo(ImplementerEvaluation::class, 'imp_eval_id');
    }

    public function responses()
    {
        return $this->hasMany(ImplementerRatingResponse::class, 'imp_rating_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImplementerRating extends Model
{
    protected $fillable = ['statement', 'status', 'order'];

    public function responses()
    {
        return $this->hasMany(ImplementerRatingResponse::class, 'imp_rating_id');
    }
}

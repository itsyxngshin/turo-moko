<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRating extends Model
{
    protected $fillable = ['statement', 'status', 'order'];

    public function responses()
    {
        return $this->hasMany(RatingResponse::class, 'prog_rating_id');
    }
}

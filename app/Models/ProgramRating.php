<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramRating extends Model
{
    protected $fillable = ['program_eval_id', 'statement'];

    public function rating()
    {
        return $this->hasMany(RatingResponse::class, 'program_rating_id');
    }

}

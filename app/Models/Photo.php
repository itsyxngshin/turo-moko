<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['photos'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

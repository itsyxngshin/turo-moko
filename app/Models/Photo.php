<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['photos'];

    
    public function profiles()
{
    return $this->hasMany(Profile::class, 'photo_id');
}


}

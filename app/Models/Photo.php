<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'photos', // add this line
    ]; // Specify the table name if it's not the plural of the model name

    
    public function profiles()
{
    return $this->hasMany(Profile::class, 'photo_id');
}


}

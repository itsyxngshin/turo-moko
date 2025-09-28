<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'photos'; // Specify the table name if it's not the plural of the model name

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}

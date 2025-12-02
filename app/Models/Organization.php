<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Organization extends Model
{
    protected $fillable = ['name'];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}

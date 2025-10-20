<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Profile extends Model
{
    protected $fillable = [
        'first_name', 'photo_id', 'last_name', 'middle_name'
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function portfolioSets()
    {
        return $this->hasMany(PortfolioSet::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'photo_id',
        'first_name',
        'middle_name',
        'last_name',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'profile_id', 'id');
    }

    public function portfolioSets()
    {
        return $this->hasMany(PortfolioSet::class);
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }
}

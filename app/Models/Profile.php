<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


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
        return $this->hasOne(User::class);
    }

    public function portfolioSets()
    {
        return $this->hasMany(PortfolioSet::class);
    }
   public function photo()
{
    return $this->belongsTo(Photo::class, 'photo_id'); // ensure this matches your DB column
}

}

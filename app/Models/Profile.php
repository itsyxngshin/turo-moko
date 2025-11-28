<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id', 'first_name', 'middle_name', 'last_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function portfolioSets()
    {
        return $this->hasMany(PortfolioSet::class);
    }
}

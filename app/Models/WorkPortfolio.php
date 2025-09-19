<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPortfolio extends Model
{
    protected $fillable = ['designation', 'description', 'duration', 'status'];

    public function portfolioSet()
    {
        return $this->hasMany(PortfolioSet::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioSet extends Model
{
    protected $fillable = ['profile_id', 'work_portfolio_id'];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function workPortfolio()
    {
        return $this->belongsTo(WorkPortfolio::class);
    }
}

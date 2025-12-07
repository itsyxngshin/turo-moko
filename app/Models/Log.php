<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'action', 
        'description', 
        'ip_address', 
        'user_agent', 
        'properties'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Polymorphic parent (optional, if you use loggable_id/_type).
     */
    public function loggable()
    {
        return $this->morphTo();
    }
}

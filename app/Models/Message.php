<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user_id',
        'conversation_id', // <-- Add this
        'body',
    ];

    // A Message belongs to one Conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // A Message belongs to one User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

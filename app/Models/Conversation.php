<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // A Conversation has many messages
    public function messages()
    {
        return $this->hasMany(Message::class)->latest(); // Good to order them here
    }
}

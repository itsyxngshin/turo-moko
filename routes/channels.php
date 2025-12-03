<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    // 1. Check if the conversation exists
    $conversation = Conversation::find($conversationId);
    
    if (!$conversation) return false;

    // 2. Check if the logged-in user is actually IN this conversation
    return $user->id === $conversation->user_one_id || 
           $user->id === $conversation->user_two_id;
});

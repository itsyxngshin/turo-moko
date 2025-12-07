<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    // 1. Log the attempt
    Log::info("Broadcasting Auth Attempt: User {$user->id} trying to join Chat {$id}");

    // 2. Find the conversation
    $conversation = Conversation::find($id);

    if (!$conversation) {
        Log::error("Auth Failed: Conversation {$id} not found.");
        return false;
    }

    // 3. Check access
    // We cast to (int) to ensure '1' (string) matches 1 (integer)
    $canAccess = (int) $user->id === (int) $conversation->user_one_id || 
                 (int) $user->id === (int) $conversation->user_two_id;

    if (!$canAccess) {
        Log::error("Auth Failed: User {$user->id} is not part of Chat {$id} (Participants: {$conversation->user_one_id}, {$conversation->user_two_id})");
    } else {
        Log::info("Auth Success!");
    }

    return $canAccess;
});

<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('conversation.{id}', function ($user, $id) {

    // Find the conversation we're trying to listen to
    $conversation = Conversation::find($id);

    // If the conversation exists AND the authenticated user
    // is part of that conversation, grant access.
    if ($conversation && $conversation->users->contains($user)) {
        // Returning an array gives user data to Echo's 'presence' features
        return ['id' => $user->id, 'name' => $user->name];
    }

    // Otherwise, deny access
    return false;
});

<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    if ($user != null) {
        return [
            'id' => $user->id,
            'name' => $user->username,
        ];
    }
    return false;
});

Broadcast::channel('chat.{receiver_id}', function ($user, $receiver_id) {
    return (int) $user->id === (int) $receiver_id;
});

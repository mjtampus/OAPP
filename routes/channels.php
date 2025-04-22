<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Comment-updates', function ($user) {
    return ['id' => $user->id, 'name' => $user->name];
});

// Broadcast::channel('users.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

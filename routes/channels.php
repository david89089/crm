<?php

use App\Models\Chat;
use App\Repository\ChatRepository;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('logs', function ($user) {
    if ($user->role('manager')){
        return true;
    }
    return false;
});

Broadcast::channel('new-user', function ($user) {
    if ($user->role('manager')){
        return true;
    }
    return false;
});

Broadcast::channel('notifications.{user_id}', function ($user, $user_id) {
    if ($user->id == $user_id){
        return true;
    }
    return false;
});

Broadcast::channel('chat.{chat_id}', function ($user, $chat_id) {
    if ($user->chats->contains($chat_id)){
        return $user->name;
    }
    return false;
});



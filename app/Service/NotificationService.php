<?php

namespace App\Service;
use App\Events\NotificationEvent;
use App\Models\Chat;
use App\Models\User;


/**
 * Class NotificationService
 * @package App\Service
 */
class NotificationService
{
    public function storeChatNotify(int $chat_id, string $message)
    {
        $chat = Chat::find($chat_id);
        foreach ($chat->users as $user) {
            if($user->id != auth()->id()) {
                NotificationEvent::dispatch($user->id, 'Chat | Owner: '.$chat->owner->name, $message);
            }
        }
    }

    public function storeUserNotify(User $user)
    {
        $managers = User::role('manager')->get();

        foreach ($managers as $manager) {
            NotificationEvent::dispatch($manager->id, 'New user', 'Name: '.$user->name);
        }
    }
}

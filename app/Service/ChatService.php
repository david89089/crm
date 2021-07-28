<?php

namespace App\Service;
use App\Models\Chat;

/**
 * Class ChatService
 */
class ChatService
{
    public static function checkChat($ownerUserId, $invitedUserId)
    {
        return Chat::query()
            ->where('invited_user_id', $invitedUserId)
            ->where('owner_user_id', $ownerUserId)
            ->orWhere(function($query) use ($ownerUserId, $invitedUserId) {
                $query ->where('invited_user_id', $ownerUserId)
                    ->where('owner_user_id', $invitedUserId);
            })->first();
    }

    public function store($invitedUserId)
    {
        return auth()->user()->chat()->create([
            'invited_user_id' => $invitedUserId,
        ]);
    }
}

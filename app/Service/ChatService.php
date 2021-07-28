<?php

namespace App\Service;
use App\Models\Chat;

/**
 * Class ChatService
 */
class ChatService
{
    public static function checkChat($user, $companion)
    {
        return Chat::query()
            ->where('companion_id', $companion)
            ->where('user_id', $user)
            ->orWhere(function($query) use ($user, $companion) {
                $query ->where('companion_id', $user)
                    ->where('user_id', $companion);
            })->first();
    }

    public function store($companion_id)
    {
        return auth()->user()->chat()->create([
            'companion_id' => $companion_id,
        ]);
    }
}

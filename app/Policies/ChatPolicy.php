<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ChatPolicy
 * @package App\Policies
 */
class ChatPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user, Chat $chat)
    {
        return $user->id === $chat->invited_user_id || $user->id === $chat->owner_user_id;
    }
}

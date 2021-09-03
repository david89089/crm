<?php

namespace App\Repositories;
use App\Enums\UsersEnum;
use App\Events\LogsEvent;
use App\Models\User;


/**
 * Class LogsRepositories
 * @package App\Repositories
 */
class LogsRepositories
{
    public function createLogStatus(User $user, int $status)
    {
        $logs = $user->logs()->create([
            'type' => $status,
            'owner_id' => auth()->id(),
            'log' => $user->name.' status updated '.UsersEnum::getNameByStatus($status),
        ])->with('user', 'owner')->latest()->first();

        LogsEvent::dispatch($logs);
    }
}

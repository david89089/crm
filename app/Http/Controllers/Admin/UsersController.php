<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UsersEnum;
use App\Http\Controllers\Controller;
use App\Models\User;

/**
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users.index', [
            'users' => $users,
            'listStatuses' => UsersEnum::listStatuses()
        ]);
    }

    public function show(int $id)
    {
        $user = User::query()->where('id', $id)->first();

        return view('admin.users.show', [
            'user' => $user,
            'listStatuses' => UsersEnum::listStatuses()
        ]);
    }
}

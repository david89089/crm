<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Enums\UsersEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStatus;
use App\Models\User;
use App\Repositories\LogsRepositories;
use Illuminate\Support\Facades\Session;

/**
 * Class UsersController
 * @package App\Http\Controllers\API\v1\Admin
 */
class UsersController extends Controller
{
    public LogsRepositories $logsRepositories;

    public function __construct(LogsRepositories $logsRepositories)
    {
        $this->logsRepositories = $logsRepositories;
    }

    public function updateStatus(UpdateStatus $request, int $status)
    {
        $user = User::find($request->input('user_id'));
        $user->status = $status;
        $user->save();

        $this->logsRepositories->createLogStatus($user, $status);

        Session::flash('status', __("Status updated ".UsersEnum::getNameByStatus($status)));
        return back();
    }
}

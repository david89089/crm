<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Enums\UsersEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateStatus;
use App\Models\User;
use App\Repository\LogsRepository;
use Illuminate\Support\Facades\Session;

/**
 * Class UsersController
 * @package App\Http\Controllers\API\v1\Admin
 */
class UsersController extends Controller
{
    public LogsRepository $logsRepository;

    public function __construct(LogsRepository $logsRepository)
    {
        $this->logsRepository = $logsRepository;
    }

    public function updateStatus(UpdateStatus $request, int $status)
    {
        $user = User::find($request->input('user_id'));
        $user->status = $status;
        $user->save();

        $this->logsRepository->createLogStatus($user, $status);

        Session::flash('status', __("Status updated ".UsersEnum::getNameByStatus($status)));
        return back();
    }
}

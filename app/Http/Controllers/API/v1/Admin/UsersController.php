<?php

namespace App\Http\Controllers\API\v1\Admin;

use App\Enums\UsersEnum;
use App\Events\LogsEvent;
use App\Http\Controllers\Controller;
use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class UsersController
 * @package App\Http\Controllers\API\v1\Admin
 */
class UsersController extends Controller
{
    public function updateStatus(Request $request, int $status)
    {
        $user = User::find($request->input('user_id'));
        $user->status = $status;
        $user->save();

        $logs = $user->logs()->create([
            'type' => $status,
            'owner_id' => auth()->id(),
            'log' => $user->name.' status updated '.UsersEnum::getNameByStatus($status),
        ])->with('user', 'owner')->latest()->first();

        LogsEvent::dispatch($logs);

        Session::flash('status', __("Status updated ".UsersEnum::getNameByStatus($status)));

        return back()->with('Update status success');
    }
}

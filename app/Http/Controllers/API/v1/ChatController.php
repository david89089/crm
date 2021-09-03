<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\UsersEnum;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Repositories\ChatRepositories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class ChatController
 * @package App\Http\Controllers\API\v1
 */
class ChatController extends Controller
{
    public ChatRepositories $ChatRepositories;
    public function __construct(ChatRepositories $ChatRepositories)
    {
        $this->ChatRepositories = $ChatRepositories;
    }

    public function store(Request $request)
    {
        $chat = $this->ChatRepositories->store($request->get('id'));

        return redirect()->route('chat.index', ['chat' => $chat->id]);
    }

    public function destroy(Request $request)
    {
        $this->ChatRepositories->destroy($request->get('id'));

        return back();
    }

    public function addUser(Request $request, Chat $chat)
    {
        $user = User::find($request->input('user_id'));

        $isChatUser = $this->ChatRepositories->isChatUser($chat, $user->id);

        if($isChatUser) {
            return back()->withErrors('Error add user');
        }

        $this->ChatRepositories->addUser($chat, $user);

        return back();
    }

    public function deleteUser(Request $request, Chat $chat)
    {
        $user = User::find($request->input('user_id'));

        $isChatUser = $this->ChatRepositories->isChatUser($chat, $user->id);

        if($isChatUser) {
            $this->ChatRepositories->deleteUser($chat, $user);
            return back();
        }

        return back()->withErrors('Error delete user');
    }
}

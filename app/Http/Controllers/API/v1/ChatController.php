<?php

namespace App\Http\Controllers\API\v1;

use App\Enums\UsersEnum;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use App\Repository\ChatRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Class ChatController
 * @package App\Http\Controllers\API\v1
 */
class ChatController extends Controller
{
    public ChatRepository $chatRepository;
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function store(Request $request)
    {
        $chat = $this->chatRepository->store($request->get('id'));

        return redirect()->route('chat.index', ['chat' => $chat->id]);
    }

    public function destroy(Request $request)
    {
        $this->chatRepository->destroy($request->get('id'));

        return back();
    }

    public function addUser(Request $request, Chat $chat)
    {
        $user = User::find($request->input('user_id'));

        $isChatUser = $this->chatRepository->isChatUser($chat, $user->id);

        if($isChatUser) {
            return back()->withErrors('Error add user');
        }

        $this->chatRepository->addUser($chat, $user);

        return back();
    }

    public function deleteUser(Request $request, Chat $chat)
    {
        $user = User::find($request->input('user_id'));

        $isChatUser = $this->chatRepository->isChatUser($chat, $user->id);

        if($isChatUser) {
            $this->chatRepository->deleteUser($chat, $user);
            return back();
        }

        return back()->withErrors('Error delete user');
    }
}

<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatRequest;
use App\Service\ChatService;
use Illuminate\Http\Request;

/**
 * Class ChatController
 * @package App\Http\Controllers\API\v1
 */
class ChatController extends Controller
{
    public ChatService $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function check(Request $request)
    {
        $chat = $this->chatService->checkChat(auth()->id(), $request->get('invited_user_id'));

        if(!$chat) return back()->withErrors('Такого чата нет :(');

        return redirect()->route('chat.index', ['id' => $chat->id]);
    }

    public function store(ChatRequest $request)
    {
        $invitedUserId = $request->get('invited_user_id');

        $chat = $this->chatService->checkChat(auth()->id(), $invitedUserId);

        if(!$chat) {
            $chat = $this->chatService->store($invitedUserId);
        }

        return redirect()->route('chat.index', ['id' => $chat->id]);
    }
}

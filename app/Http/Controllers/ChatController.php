<?php

namespace App\Http\Controllers;

use App\Models\Chat;

/**
 * Class ChatController
 * @package App\Http\Controllers
 */
class ChatController extends Controller
{
    public function index(int $id)
    {
        $chat = Chat::find($id);
        if(!$chat) return back()->withErrors('Ошибка, чата не существует :(');

        $this->authorize('index', $chat);

        return view('chat', ['chat' => $chat]);
    }
}

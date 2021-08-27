<?php

namespace App\Http\Controllers;

use App\Events\NotificationEvent;
use App\Models\Chat;
use Illuminate\Http\Request;

/**
 * Class ChatController
 * @package App\Http\Controllers
 */
class ChatController extends Controller
{
    public function index(Request $request, Chat $chat)
    {
        $data = $chat->with(['owner', 'messages' => function($q){
            $q->with('user');
        }])->where('id', $chat->id)->first();

        if(!$data) return back()->withErrors('Ошибка, чата не существует :(');

        foreach ($data->messages as $message) {
            if($message->user_id != auth()->id()) {
                $message->read = true;
                $message->save();
            }
        }

        return view('chat', ['chat' => $data]);
    }
}

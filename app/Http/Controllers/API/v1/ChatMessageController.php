<?php

namespace App\Http\Controllers\API\v1;

use App\Events\NotificationEvent;
use App\Events\PrivateChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatMessageRequest;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

/**
 * Class ChatMessageController
 * @package App\Http\Controllers\API\v1
 */
class ChatMessageController extends Controller
{
    public function store(ChatMessageRequest $request)
    {
        $data = ChatMessage::create([
            'chat_id' => $request->get('chat_id'),
            'user_id' => auth()->id(),
            'message' => $request->get('message'),
        ])->with('user')->latest()->first();

        PrivateChatEvent::dispatch($data);

        $chat = Chat::find($data->chat_id);
        foreach ($chat->users as $user) {
            if($user->id != auth()->id()) {
                NotificationEvent::dispatch($user->id, 'Chat | Owner: '.$data->chat->owner->name, $data->message);
            }
        }

        return response()->json($data);
    }
}

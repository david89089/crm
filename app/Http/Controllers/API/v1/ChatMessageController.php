<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatMessageRequest;
use App\Models\Chat;

/**
 * Class ChatMessageController
 * @package App\Http\Controllers\API\v1
 */
class ChatMessageController extends Controller
{
    public function store(ChatMessageRequest $request)
    {
        Chat::find($request->get('chat_id'))->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->get('message'),
        ]);

        return back();
    }
}

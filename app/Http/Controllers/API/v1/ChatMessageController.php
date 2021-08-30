<?php

namespace App\Http\Controllers\API\v1;

use App\Events\PrivateChatEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\ChatMessageRequest;
use App\Repository\ChatRepository;
use App\Service\NotificationService;

/**
 * Class ChatMessageController
 * @package App\Http\Controllers\API\v1
 */
class ChatMessageController extends Controller
{
    public ChatRepository $chatRepository;
    public NotificationService $notificationService;

    public function __construct(ChatRepository $chatRepository,
                                NotificationService $notificationService)
    {
        $this->chatRepository = $chatRepository;
        $this->notificationService = $notificationService;
    }

    public function store(ChatMessageRequest $request)
    {
        $data = $this->chatRepository->storeMessage($request->input('chat_id'), $request->input('message'));

        PrivateChatEvent::dispatch($data);

        $this->notificationService->storeChatNotify($data->chat_id, $data->message);

        return response()->json($data);
    }
}

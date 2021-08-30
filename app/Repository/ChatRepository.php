<?php

namespace App\Repository;
use App\Events\PrivateChatEvent;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Session;

/**
 * Class ChatRepository
 */
class ChatRepository
{
    /**
     * @param int $user_id
     * @return mixed
     */
    public function store(int $user_id)
    {
        $user = User::where('id', $user_id)->first();

        if(!$user->chat) {
            $chat = $user->chat()->create();
            $chat->users()->attach(['user_id' => $user->id]);
            return $chat;
        }
        return with('Error create chat');
    }

    /**
     * @param int $user_id
     * @return mixed
     */
    public function destroy(int $user_id)
    {
        $chat = Chat::where('user_id', $user_id);
        Session::flash('status', __('Chat deleted'));
        return $chat->delete();
    }

    /**
     * @param Chat $chat
     * @param int $user_id
     * @return mixed
     */
    public static function isChatUser(Chat $chat, int $user_id)
    {
        $user = User::find($user_id);

        return $user->chats->contains($chat->id);
    }

    public function addUser(Chat $chat, User $user)
    {
        $chat->users()->attach([
            'user_id' => $user->id
        ]);

        return Session::flash('status', __('Chat add user '. $user->name));
    }

    public function deleteUser(Chat $chat, User $user)
    {
        $chat->users()->detach($user->id);

        return Session::flash('status', __('Success delete user '. $user->name));
    }

    public function storeMessage(int $chat_id, string $message)
    {
        $data = ChatMessage::create([
            'chat_id' => $chat_id,
            'user_id' => auth()->id(),
            'message' => $message,
        ])->with('user')->latest()->first();
        return $data;
    }
}

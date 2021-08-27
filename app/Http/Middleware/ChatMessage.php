<?php

namespace App\Http\Middleware;

use App\Models\Chat;
use Closure;
use Illuminate\Http\Request;

/**
 * Class ChatMessage
 * @package App\Http\Middleware
 */
class ChatMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $chat = Chat::find($request->input('chat_id'));
        if($chat->users->contains(\auth()->id()))
            return $next($request);

        return redirect('/');
    }
}

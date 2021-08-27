<?php

namespace App\Http\Middleware;

use App\Models\Chat;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class AccessChat
 * @package App\Http\Middleware
 */
class AccessChat
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Chat $chat
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->chat->users->contains(\auth()->id())) {
            return $next($request);
        }
        return redirect('/');
    }
}

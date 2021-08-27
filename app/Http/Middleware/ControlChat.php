<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class ControlChat
 * @package App\Http\Middleware
 */
class ControlChat
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
        if($request->chat->user_id == \auth()->id()) {
            return $next($request);
        }
        return redirect('/');
    }
}

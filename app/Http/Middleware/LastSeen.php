<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Log;
use Carbon\Carbon;

class LastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check())
        {
            return $next($request);
        }

        $user = Auth::user();
        $user->last_seen = Carbon::now();
        $user->save();
        Log::debug($user->name." last seen ".$user->last_seen->toRfc850String());
        return $next($request);
    }
}

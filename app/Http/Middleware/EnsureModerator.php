<?php

namespace App\Http\Middleware;

use Closure;

class EnsureModerator
{
    public function handle($request, Closure $next)
    {
        if(!$request->user() || !$request->user()->isModerator()){
            abort(403);
        }

        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // 1. Check whether the user is logged in and has a role.
        if (!$request->user()) {
            abort(403, 'Unauthorized.');
        }

        // 2. Check whether the current role is in the allowed roles list.
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Block users without one of the allowed roles.
        abort(403, 'You do not have permission to access this page.');
    }
}


// namespace App\Http\Middleware;

// use Closure;

// class RoleMiddleware
// {
//     public function handle($request, Closure $next, $role)
//     {
//         if(!$request->user() || $request->user()->role !== $role){
//             abort(403);
//         }

//         return $next($request);
//     }
// }

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
     * @param  string  ...$roles  <-- Perhatikan titik tiga ini (Variadic Parameter)
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // 1. Cek apakah user sudah login dan punya role
        if (!$request->user()) {
            abort(403, 'Unauthorized.');
        }

        // 2. Cek apakah role user saat ini ada di dalam list array $roles yang diizinkan
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya salah satu role di atas, blokir
        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
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
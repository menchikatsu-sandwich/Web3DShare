<?php

use App\Http\Middleware\ForceJsonResponse;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        $middleware->append(SecurityHeaders::class);

        $middleware->api(prepend: [
            ForceJsonResponse::class,
        ]);

        $middleware->web(append: [
            // Keep RoleMiddleware out of the default web stack so public pages stay open.
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booting(function () {
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
        Gate::define('moderator', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
    })
    ->create();

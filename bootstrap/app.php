<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Gate; // Tambahkan ini
use App\Models\User; // Tambahkan ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            // Kosongkan bagian ini dari RoleMiddleware agar tidak berjalan otomatis di halaman home
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booting(function () { // Tambahkan logika Gate di sini
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
        Gate::define('moderator', function (User $user) {
            return in_array($user->role, ['admin', 'moderator']);
        });
    })
    ->create();
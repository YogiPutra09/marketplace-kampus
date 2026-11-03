<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Konfigurasi redirect login / tamu (Admin ke dashboard, user biasa ke products)
        $middleware->redirectTo(
            guests: '/login',
            users: function ($request) {
                if ($request->user() && $request->user()->isAdmin()) {
                    return route('admin.dashboard');
                }
                return route('products.index');
            },
        );

        // 2. Tambahkan pengecekan akun diblokir untuk semua route web
        $middleware->web(append: [
            \App\Http\Middleware\CheckBannedStatus::class,
        ]);

        // 3. Alias middleware route
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
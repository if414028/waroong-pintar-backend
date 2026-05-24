<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'store.access' => \App\Http\Middleware\EnsureUserHasStoreAccess::class,
            'subscription.active' => \App\Http\Middleware\EnsureStoreSubscriptionIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle token invalid / expired
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'code' => 'TOKEN_EXPIRED',
                'message' => 'Token expired or invalid. Please login again.'
            ], 401);
        });

        // Handle error umum biar gak HTML
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        });
    })->create();

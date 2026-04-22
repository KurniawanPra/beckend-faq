<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Pastikan semua request ke /api/* mendapat Accept: application/json
        // agar Sanctum tidak mencoba redirect ke route 'login' yang tidak ada
        $middleware->prepend(\App\Http\Middleware\ForceJsonOnApiRequests::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // ─── 401 Unauthenticated ───────────────────────────────────────────────
        // Override unauthenticated handler sehingga API selalu dapat JSON (bukan redirect)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'success' => false,
                'error'   => 'Unauthenticated. Token tidak ada atau sudah expired.',
            ], 401);
        });

        // ─── 422 Validation ───────────────────────────────────────────────────
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Validasi gagal',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // ─── 404 Not Found ────────────────────────────────────────────────────
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'error'   => 'Resource tidak ditemukan',
                ], 404);
            }
        });

    })->create();

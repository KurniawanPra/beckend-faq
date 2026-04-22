<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk memastikan semua request ke /api/* mendapatkan
 * header Accept: application/json secara otomatis.
 * Ini mencegah Sanctum melakukan redirect ke route 'login' yang tidak ada,
 * sehingga selalu menghasilkan response JSON 401.
 */
class ForceJsonOnApiRequests
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request);
    }
}

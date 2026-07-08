<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CachePublicResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, int $seconds = 300): Response
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $response->headers->set('Cache-Control', "public, max-age={$seconds}");
        }

        return $response;
    }
}

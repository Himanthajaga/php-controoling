<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRouteAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Log the incoming request
        Log::channel('single')->info('Route accessed', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'url' => $request->url(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        // Call the next middleware/handler
        $response = $next($request);

        // Log the response status
        Log::channel('single')->info('Route response', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'status' => $response->getStatusCode(),
            'timestamp' => now(),
        ]);

        return $response;
    }
}


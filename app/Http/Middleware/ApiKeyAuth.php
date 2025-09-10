<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        // Check if API key exists
        if (!$apiKey) {
            return response()->json([
                'error' => 'API key is required',
                'message' => 'Please provide X-API-Key header'
            ], 401);
        }

        // Validate API key (you can check database here)
        $validKeys = ['your-secret-api-key-124', 'another-valid-key-456'];

        if (!in_array($apiKey, $validKeys)) {
            return response()->json([
                'error' => 'Invalid API key',
                'message' => 'The provided API key is not valid'
            ], 401);
        }

        return $next($request);
    }
}

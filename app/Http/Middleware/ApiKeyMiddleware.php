<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $apiKeyHeader = $request->header('X-API-KEY');
        $expectedApiKey = env('API_KEY');

        if (!$apiKeyHeader || !$expectedApiKey || $apiKeyHeader !== $expectedApiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key',
                'error' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}

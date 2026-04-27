<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPublicApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('app.require_api_key', true)) {
            $apiKey = $request->header('X-API-KEY');

            if (!$apiKey || $apiKey !== config('app.public_api_key')) {
                return response()->json([
                    'message' => 'Unauthenticated',
                    'error' => 'Invalid or missing API key'
                ], 401);
            }
        }

        return $next($request);
    }
}
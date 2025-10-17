<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Create cache key from URL and query parameters
        $cacheKey = 'http_cache_' . md5($request->fullUrl());
        
        // Check if we have cached response
        if (Cache::has($cacheKey)) {
            $cachedResponse = Cache::get($cacheKey);
            return response($cachedResponse['content'])
                ->withHeaders($cachedResponse['headers']);
        }

        $response = $next($request);

        // Only cache successful responses
        if ($response->getStatusCode() === 200) {
            $cacheData = [
                'content' => $response->getContent(),
                'headers' => [
                    'Content-Type' => $response->headers->get('Content-Type'),
                    'X-Cached' => 'true'
                ]
            ];
            
            // Cache for 5 minutes
            Cache::put($cacheKey, $cacheData, 300);
        }

        return $response;
    }
}

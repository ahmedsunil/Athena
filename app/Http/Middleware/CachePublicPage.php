<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves fully rendered public pages from cache for guests, skipping
 * controller work and database queries. The cache is invalidated whenever
 * CMS content changes (see AppServiceProvider::bootPageCacheInvalidation).
 */
class CachePublicPage
{
    public const VERSION_KEY = 'page-cache-version';

    private const TTL_SECONDS = 600;

    /** Query parameters the public pages understand; anything else skips the cache. */
    private const CACHEABLE_QUERY_KEYS = [
        'activeTab', 'activeCategory', 'activeYear', 'activeMonth', 'activeAudience',
        'activeCalendarId', 'calendarMonth', 'page', 'type', 'category',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isCacheable($request)) {
            return $next($request);
        }

        $store = Cache::store();
        $key = $this->cacheKey($request, $store->get(self::VERSION_KEY, '1'));

        if ($cached = $store->get($key)) {
            return response($cached['content'], 200, [
                'Content-Type' => $cached['content_type'],
                'X-Page-Cache' => 'HIT',
            ]);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 200 && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $store->put($key, [
                'content' => $response->getContent(),
                'content_type' => $response->headers->get('Content-Type'),
            ], self::TTL_SECONDS);
            $response->headers->set('X-Page-Cache', 'MISS');
        }

        return $response;
    }

    private function isCacheable(Request $request): bool
    {
        if (! $request->isMethod('GET') || $request->user()) {
            return false;
        }

        $debugbarOn = class_exists(\Fruitcake\LaravelDebugbar\LaravelDebugbar::class)
            && (config('debugbar.enabled') ?? config('app.debug'));

        if ($debugbarOn) {
            return false; // debugbar injects per-request data into the HTML
        }

        $query = $request->query();

        return strlen((string) $request->getQueryString()) <= 300
            && array_diff(array_keys($query), self::CACHEABLE_QUERY_KEYS) === []
            && collect($query)->every(fn ($value) => is_scalar($value));
    }

    private function cacheKey(Request $request, string $version): string
    {
        $query = $request->query();
        ksort($query);

        return implode(':', [
            'page', $version, app()->getLocale(), now()->toDateString(),
            md5($request->path().'?'.http_build_query($query)),
        ]);
    }
}

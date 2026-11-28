<?php

namespace App\Providers;

use App\Http\Middleware\CachePublicPage;
use App\Models\FooterLink;
use App\Models\SchoolProfile;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

        $this->bootPageCacheInvalidation();

        RateLimiter::for('contact', fn (Request $request) =>
            Limit::perMinute(5)->by($request->ip())
        );

        View::composer('layouts.partials.footer', function ($view) {
            [$footerProfile, $footerLinks] = Cache::store('file')->remember('footer_data', now()->addDay(), function () {
                $profile = SchoolProfile::singleton();
                return [
                    [
                        'school_name' => $profile->school_name,
                        'motto'       => $profile->motto,
                        'logo_path'   => $profile->logo_path,
                        'logo_url'    => $profile->logo_url,
                        'email'       => $profile->email,
                        'phone'       => $profile->phone,
                        'island'      => $profile->island,
                        'atoll'       => $profile->atoll,
                        'country'     => $profile->country,
                    ],
                    FooterLink::where('is_active', true)->orderBy('sort_order')->get()
                        ->map(fn ($l) => [
                            'link_key' => $l->link_key,
                            'label'    => $l->label,
                        ])
                        ->all(),
                ];
            });

            $footerLinks = collect($footerLinks)->map(fn ($l) => (object) $l);

            $view->with(compact('footerProfile', 'footerLinks'));
        });
    }

    /**
     * Any change to site content (App\Models, except users) starts a new
     * page-cache version, so cached public pages are never served stale.
     */
    private function bootPageCacheInvalidation(): void
    {
        Event::listen(['eloquent.saved: *', 'eloquent.deleted: *'], function (string $event, array $payload) {
            $model = $payload[0] ?? null;

            if ($model instanceof Model && str_starts_with($model::class, 'App\\Models\\') && ! $model instanceof User) {
                Cache::store()->forever(CachePublicPage::VERSION_KEY, (string) Str::uuid());
                Cache::store('file')->forget('footer_data');
            }
        });
    }
}

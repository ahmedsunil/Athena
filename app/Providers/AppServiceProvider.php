<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\SchoolProfile;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

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
}

<?php

namespace App\Providers;

use App\Models\FooterLink;
use App\Models\SchoolProfile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();

        View::composer('layouts.partials.footer', function ($view) {
            $profile = SchoolProfile::singleton();
            $view->with([
                'footerProfile' => [
                    'school_name' => $profile->school_name,
                    'motto' => $profile->motto,
                    'logo_path' => $profile->logo_path,
                    'logo_url' => $profile->logo_url,
                    'email' => $profile->email,
                    'phone' => $profile->phone,
                    'island' => $profile->getRawOriginal('island'),
                    'atoll' => $profile->getRawOriginal('atoll'),
                    'country' => $profile->getRawOriginal('country'),
                ],
                'footerLinks' => FooterLink::where('is_active', true)->orderBy('sort_order')->get(),
            ]);
        });
    }
}

<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Site\SiteController;
use App\Livewire\AppManagement\SystemSettings;
use App\Livewire\Auditing\ActivityLog;
use App\Livewire\Auditing\ActivityLogShow;
use App\Livewire\Cms\FooterLinks\FooterLinksIndex;
use App\Livewire\Cms\HomeQuickAccess\HomeQuickAccessIndex;
use App\Livewire\Cms\HomeSlides\HomeSlidesIndex;
use App\Livewire\Cms\HomeStats\HomeStatsIndex;
use App\Livewire\Cms\HomeTestimonials\HomeTestimonialsIndex;
use App\Livewire\Cms\Icons\IconsIndex;
use App\Livewire\Cms\Links\LinksIndex;
use App\Livewire\Cms\SchoolProfile\SchoolProfileEdit;
use App\Livewire\Dashboard;
use App\Livewire\Profile\ProfileSettings;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Roles\RolesList;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserShow;
use App\Livewire\Users\UsersList;
use Illuminate\Support\Facades\Route;

// Public website
Route::controller(SiteController::class)->name('site.')->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/about', 'about')->name('about');
    Route::get('/academics', 'academics')->name('academics');
    Route::get('/admissions', 'admissions')->name('admissions');
    Route::get('/events', 'events')->name('events.index');
    Route::get('/events/{slug}', 'event')->name('events.show');
    Route::get('/event.html', 'legacyEvent')->name('events.legacy');
    Route::get('/student-life', 'studentLife')->name('student-life');
    Route::get('/gallery', 'gallery')->name('gallery');
    Route::get('/downloads', 'downloads')->name('downloads');
    Route::get('/digital-services', 'digitalServices')->name('digital-services');
    Route::get('/search', 'search')->name('search');
});

Route::redirect('/index.html', '/');
Route::redirect('/about.html', '/about');
Route::redirect('/events.html', '/events');
Route::redirect('/academics.html', '/academics');
Route::redirect('/admissions.html', '/admissions');
Route::redirect('/student-life.html', '/student-life');
Route::redirect('/gallery.html', '/gallery');
Route::redirect('/downloads.html', '/downloads');
Route::redirect('/digital-services.html', '/digital-services');
Route::redirect('/search.html', '/search');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // User management
    Route::get('/users', UsersList::class)->name('users.index');
    Route::get('/users/create', UserForm::class)->name('users.create');
    Route::get('/users/{userId}', UserShow::class)->whereNumber('userId')->name('users.show');
    Route::get('/users/{userId}/edit', UserForm::class)->name('users.edit');
    // Roles management
    Route::get('/roles', RolesList::class)->name('roles.index');
    Route::get('/roles/create', RoleForm::class)->name('roles.create');
    Route::get('/roles/{roleId}', RoleShow::class)->whereNumber('roleId')->name('roles.show');
    Route::get('/roles/{roleId}/edit', RoleForm::class)->name('roles.edit');

    // App Management
    Route::get('/app/activity', ActivityLog::class)->name('app.activity');
    Route::get('/app/activity/{activityId}', ActivityLogShow::class)
        ->whereNumber('activityId')
        ->name('app.activity.show');
    Route::get('/app/settings', SystemSettings::class)->name('app.settings');

    // CMS — config registries
    Route::get('/cms/links', LinksIndex::class)->name('cms.links.index');
    Route::get('/cms/icons', IconsIndex::class)->name('cms.icons.index');

    // CMS — content modules
    Route::get('/cms/school-profile', SchoolProfileEdit::class)->name('cms.school-profile');
    Route::get('/cms/footer-links', FooterLinksIndex::class)->name('cms.footer-links');
    Route::get('/cms/home/slides', HomeSlidesIndex::class)->name('cms.home.slides');
    Route::get('/cms/home/stats', HomeStatsIndex::class)->name('cms.home.stats');
    Route::get('/cms/home/quick-access', HomeQuickAccessIndex::class)->name('cms.home.quick-access');
    Route::get('/cms/home/testimonials', HomeTestimonialsIndex::class)->name('cms.home.testimonials');
    // Profile & settings
    Route::get('/profile', ProfileSettings::class)->name('profile.index');

});

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

<?php

use App\Http\Controllers\GoogleAuthController;
use App\Livewire\AppManagement\SystemSettings;
use App\Livewire\Auditing\ActivityLog;
use App\Livewire\Auditing\ActivityLogShow;
use App\Livewire\Cms\Icons\IconsIndex;
use App\Livewire\Cms\Links\LinksIndex;
use App\Livewire\Dashboard;
use App\Livewire\Profile\ProfileSettings;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RolesList;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UsersList;
use Illuminate\Support\Facades\Route;

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    // User management
    Route::get('/users', UsersList::class)->name('users.index');
    Route::get('/users/create', UserForm::class)->name('users.create');
    Route::get('/users/{userId}/edit', UserForm::class)->name('users.edit');
    // Roles management
    Route::get('/roles', RolesList::class)->name('roles.index');
    Route::get('/roles/create', RoleForm::class)->name('roles.create');
    Route::get('/roles/{roleId}/edit', RoleForm::class)->name('roles.edit');

    // App Management
    Route::get('/app/activity', ActivityLog::class)->name('app.activity');
    Route::get('/app/activity/{activityId}', ActivityLogShow::class)
        ->whereNumber('activityId')
        ->name('app.activity.show');
    Route::get('/app/settings', SystemSettings::class)->name('app.settings');

    // CMS — Links
    Route::get('/cms/links', LinksIndex::class)->name('cms.links.index');

    // CMS — Icons
    Route::get('/cms/icons', IconsIndex::class)->name('cms.icons.index');

    // Profile & settings
    Route::get('/profile', ProfileSettings::class)->name('profile.index');

});

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Root redirect
Route::redirect('/', '/dashboard');

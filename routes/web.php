<?php

use App\Http\Controllers\GoogleAuthController;
use App\Livewire\AppManagement\SystemSettings;
use App\Livewire\Auditing\ActivityLog;
use App\Livewire\Cms\Home\HomeIndex;
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
    Route::get('/app/settings', SystemSettings::class)->name('app.settings');

    // Profile & settings
    Route::get('/profile', ProfileSettings::class)->name('profile.index');

    // CMS
    Route::get('/home', HomeIndex::class)->name('cms.home');

    // School Profile
    Route::get('/school-profile', App\Livewire\Cms\SchoolProfile\Show::class)->name('school-profile.show');

});

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Root redirect
Route::redirect('/', '/dashboard');

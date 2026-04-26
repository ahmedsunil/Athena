<?php

use App\Livewire\Dashboard;
use App\Livewire\Profile\ProfileSettings;
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

    // Profile & settings
    Route::get('/profile', ProfileSettings::class)->name('profile.index');
});

// Root redirect
Route::redirect('/', '/dashboard');

<?php

use App\Http\Controllers\ContactMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/contact', [ContactMessageController::class, 'store'])->name('api.contact.store');

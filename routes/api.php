<?php

use App\Http\Controllers\API\SchoolProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('public.api')->group(function () {
    Route::get('/school-profile', [SchoolProfileController::class, 'show']);
});

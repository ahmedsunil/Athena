<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class PublicConfigController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json([
            'api_key' => config('app.public_api_key'),
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
        ]);
    }
}
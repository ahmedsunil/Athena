<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeApiResource;
use App\Models\HomePage;

class HomeController extends Controller
{
    public function show(): HomeApiResource
    {
        $homePage = HomePage::first();

        return new HomeApiResource($homePage);
    }
}

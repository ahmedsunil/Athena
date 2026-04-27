<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\SchoolProfileResource;
use App\Models\SchoolProfile;

class SchoolProfileController extends Controller
{
    public function show()
    {
        $profile = SchoolProfile::first();

        if (!$profile) {
            return response()->json(['message' => 'School profile not found'], 404);
        }

        return new SchoolProfileResource($profile);
    }
}

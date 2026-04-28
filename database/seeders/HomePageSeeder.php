<?php

namespace Database\Seeders;

use App\Models\HomePage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $path = base_path('api_jsons/home.json');

        if (! File::exists($path)) {
            return;
        }

        $payload = File::get($path);
        json_decode($payload, true, flags: JSON_THROW_ON_ERROR);

        HomePage::updateOrCreate(
            ['id' => 1],
            [
                'payload' => $payload,
            ]
        );
    }
}

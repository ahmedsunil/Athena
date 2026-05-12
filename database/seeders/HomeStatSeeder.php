<?php

namespace Database\Seeders;

use App\Models\HomeStat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomeStatSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $stats = $homeJson['stats'] ?? [];

        $titles = collect($stats)->pluck('label');
        $existingMaxSortOrder = HomeStat::whereNotIn('title', $titles)->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($stats as $index => $stat) {
            HomeStat::updateOrCreate(
                ['title' => $stat['label']],
                [
                    'value' => $stat['value'],
                    'is_active' => true,
                    'sort_order' => $baseSortOrder + $index,
                ]
            );
        }
    }
}

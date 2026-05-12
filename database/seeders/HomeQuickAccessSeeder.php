<?php

namespace Database\Seeders;

use App\Models\HomeQuickAccess;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomeQuickAccessSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $quickLinks = $homeJson['quickLinks'] ?? [];

        $titles = collect($quickLinks)->pluck('label');
        $existingMaxSortOrder = HomeQuickAccess::whereNotIn('title', $titles)->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($quickLinks as $index => $quickLink) {
            HomeQuickAccess::updateOrCreate(
                ['title' => $quickLink['label']],
                [
                    'icon_key' => $quickLink['icon'],
                    'link_key' => $quickLink['href'],
                    'is_active' => true,
                    'sort_order' => $baseSortOrder + $index,
                ]
            );
        }
    }
}

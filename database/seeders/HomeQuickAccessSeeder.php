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

        $existing = HomeQuickAccess::all()->keyBy(fn ($q) => $q->getTranslation('title', 'en', false));

        $seededLabels = collect($quickLinks)->pluck('label');
        $existingMaxSortOrder = HomeQuickAccess::all()
            ->filter(fn ($q) => ! $seededLabels->contains($q->getTranslation('title', 'en', false)))
            ->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($quickLinks as $index => $quickLink) {
            $data = [
                'title'      => ['en' => $quickLink['label']],
                'icon_key'   => $quickLink['icon'],
                'link_key'   => $quickLink['href'],
                'is_active'  => true,
                'sort_order' => $baseSortOrder + $index,
            ];

            if ($match = $existing->get($quickLink['label'])) {
                $match->update($data);
            } else {
                HomeQuickAccess::create($data);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomeSlideSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $slides = $homeJson['slides'] ?? [];

        $existing = HomeSlide::all()->keyBy(fn ($s) => $s->getTranslation('title', 'en', false));

        $seededTitles = collect($slides)->pluck('title');
        $existingMaxSortOrder = HomeSlide::all()
            ->filter(fn ($s) => ! $seededTitles->contains($s->getTranslation('title', 'en', false)))
            ->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($slides as $index => $slide) {
            $data = [
                'title'             => ['en' => $slide['title']],
                'description'       => ['en' => $slide['subtitle'] ?? ''],
                'image_path'        => $slide['imageUrl'] ?? null,
                'button_1_label'    => ['en' => $slide['ctaLabel'] ?? ''],
                'button_1_link_key' => $slide['ctaHref'] ?? null,
                'button_2_label'    => ['en' => ''],
                'button_2_link_key' => null,
                'is_active'         => true,
                'sort_order'        => $baseSortOrder + $index,
            ];

            if ($match = $existing->get($slide['title'])) {
                $match->update($data);
            } else {
                HomeSlide::create($data);
            }
        }
    }
}

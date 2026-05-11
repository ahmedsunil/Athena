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

        $titles = collect($slides)->pluck('title');
        $existingMaxSortOrder = HomeSlide::whereNotIn('title', $titles)->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($slides as $index => $slide) {
            HomeSlide::updateOrCreate(
                ['title' => $slide['title']],
                [
                    'description' => $slide['subtitle'] ?? null,
                    'image_path' => $slide['imageUrl'] ?? null,
                    'button_1_label' => $slide['ctaLabel'] ?? null,
                    'button_1_link_key' => $slide['ctaHref'] ?? null,
                    'button_2_label' => null,
                    'button_2_link_key' => null,
                    'is_active' => true,
                    'sort_order' => $baseSortOrder + $index,
                ]
            );
        }
    }
}

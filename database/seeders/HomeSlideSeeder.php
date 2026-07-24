<?php

namespace Database\Seeders;

use App\Models\HomeSlide;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($slides) {
            HomeSlide::query()->delete();

            foreach ($slides as $index => $slide) {
                HomeSlide::create([
                    'title'             => $this->translation($slide, 'title'),
                    'description'       => $this->translation($slide, 'subtitle'),
                    'image_path'        => $slide['imageUrl'] ?? null,
                    'button_1_label'    => $this->translation($slide, 'ctaLabel'),
                    'button_1_link_key' => $slide['ctaHref'] ?? null,
                    'button_2_label'    => ['en' => ''],
                    'button_2_link_key' => null,
                    'is_active'         => true,
                    'sort_order'        => $index,
                ]);
            }
        });
    }

    private function translation(array $item, string $key): array
    {
        return [
            'en' => $item[$key] ?? '',
        ];
    }
}

<?php

namespace Database\Seeders;

use App\Models\HomeTestimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class HomeTestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $homePath = public_path('api/home.json');

        if (! File::exists($homePath)) {
            return;
        }

        $homeJson = json_decode(File::get($homePath), true, flags: JSON_THROW_ON_ERROR);
        $testimonials = $homeJson['testimonials'] ?? [];

        $existing = HomeTestimonial::all()->keyBy('name');

        $seededNames = collect($testimonials)->pluck('author');
        $existingMaxSortOrder = HomeTestimonial::all()
            ->filter(fn ($t) => ! $seededNames->contains($t->name))
            ->max('sort_order');
        $baseSortOrder = $existingMaxSortOrder === null ? 0 : $existingMaxSortOrder + 1;

        foreach ($testimonials as $index => $testimonial) {
            $data = [
                'photo_path'            => $testimonial['photoUrl'] ?? null,
                'previous_designation'  => null,
                'current_designation'   => ['en' => $testimonial['role'] ?? ''],
                'message'               => ['en' => $testimonial['quote']],
                'is_active'             => true,
                'sort_order'            => $baseSortOrder + $index,
            ];

            if ($match = $existing->get($testimonial['author'])) {
                $match->update($data);
            } else {
                HomeTestimonial::create(array_merge($data, ['name' => $testimonial['author']]));
            }
        }
    }
}

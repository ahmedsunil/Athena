<?php

namespace Database\Seeders;

use App\Models\HomeTestimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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

        DB::transaction(function () use ($testimonials) {
            HomeTestimonial::query()->delete();

            foreach ($testimonials as $index => $testimonial) {
                HomeTestimonial::create([
                    'photo_path'            => $testimonial['photoUrl'] ?? null,
                    'name'                  => $testimonial['author'],
                    'previous_designation'  => ['en' => ''],
                    'current_designation'   => $this->translation($testimonial, 'role'),
                    'message'               => $this->translation($testimonial, 'quote'),
                    'is_active'             => true,
                    'sort_order'            => $index,
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class HomePageSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SchoolProfileSeeder::class,
            HomeSlideSeeder::class,
            HomeStatSeeder::class,
            HomeQuickAccessSeeder::class,
            HomeTestimonialSeeder::class,
        ]);
    }
}

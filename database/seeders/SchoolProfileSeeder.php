<?php

namespace Database\Seeders;

use App\Models\SchoolProfile;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        SchoolProfile::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Bright Future Academy',
                'founded_year' => 1995,
                'motto' => 'Educating Tomorrow\'s Leaders',
                'tagline' => 'Excellence in Education',
                'description' => 'A premier educational institution dedicated to nurturing young minds and fostering academic excellence.',
                'logo_path' => 'school/logo.png',
                'hero_image_path' => 'school/hero.jpg',
                'mission_statement' => 'To provide quality education that inspires and empowers students to become responsible global citizens.',
                'vision_statement' => 'To be a leading educational institution that transforms lives through innovative learning.',
                'contact_address' => '123 Education Street, City, Country',
                'contact_phone' => '+960 123 4567',
                'contact_email' => 'info@brightfuture.edu',
            ]
        );
    }
}
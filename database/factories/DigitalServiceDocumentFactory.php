<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalServiceDocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sort_order'   => 0,
            'title'        => $this->faker->sentence(4),
            'category'     => $this->faker->randomElement(['Forms & Applications', 'Policies & Handbooks', 'Timetables & Schedules', 'Academic Resources']),
            'file_type'    => $this->faker->randomElement(['PDF', 'DOCX']),
            'file_size'    => $this->faker->numerify('### KB'),
            'audience'     => $this->faker->randomElement(['All', 'Students', 'Parents', 'Staff']),
            'published_at' => $this->faker->date(),
            'file_path'    => null,
            'is_active'    => true,
        ];
    }
}

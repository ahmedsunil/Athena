<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalServiceCalendarEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sort_order'  => 0,
            'title'       => $this->faker->sentence(4),
            'date'        => $this->faker->date(),
            'end_date'    => null,
            'type'        => $this->faker->randomElement(['event', 'term', 'holiday', 'exam']),
            'description' => $this->faker->sentence(),
            'is_active'   => true,
        ];
    }
}

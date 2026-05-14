<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DigitalServiceResourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sort_order'  => 0,
            'title'       => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'audience'    => $this->faker->randomElement(['All', 'Students', 'Parents', 'Staff']),
            'icon'        => 'BookOpen',
            'icon_color'  => $this->faker->randomElement(['sky', 'rose', 'emerald', 'amber', 'violet', 'slate']),
            'url'         => $this->faker->url(),
            'is_active'   => true,
        ];
    }
}

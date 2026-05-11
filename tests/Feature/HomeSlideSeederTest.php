<?php

namespace Tests\Feature;

use App\Models\HomeSlide;
use Database\Seeders\HomeSlideSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSlideSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_slides_are_seeded_from_public_home_json(): void
    {
        $this->seed(HomeSlideSeeder::class);

        $this->assertDatabaseHas('home_slides', [
            'title' => "Shaping Tomorrow's Leaders",
            'button_1_label' => 'Apply for Admission',
            'button_1_link_key' => '/admissions',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $slide = HomeSlide::where('title', "Shaping Tomorrow's Leaders")->firstOrFail();

        $this->assertSame(
            'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1600&q=80',
            $slide->image_url
        );
    }

    public function test_home_slide_seeder_is_idempotent(): void
    {
        $this->seed(HomeSlideSeeder::class);
        $this->seed(HomeSlideSeeder::class);

        $this->assertSame(3, HomeSlide::count());
    }
}

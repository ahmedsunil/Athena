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

        $slide = HomeSlide::all()->first(fn ($s) => $s->getTranslation('title', 'en', false) === "Shaping Tomorrow's Leaders");

        $this->assertNotNull($slide);
        $this->assertSame('Apply for Admission', $slide->getTranslation('button_1_label', 'en', false));
        $this->assertSame('/admissions', $slide->button_1_link_key);
        $this->assertSame(0, $slide->sort_order);
        $this->assertTrue($slide->is_active);
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

    public function test_home_slide_seeder_overwrites_existing_rows(): void
    {
        HomeSlide::create([
            'title' => ['en' => 'Old Slide'],
            'description' => ['en' => 'Old'],
            'button_1_label' => ['en' => 'Old'],
            'button_2_label' => ['en' => ''],
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $this->seed(HomeSlideSeeder::class);

        $this->assertSame(3, HomeSlide::count());
        $this->assertNull(HomeSlide::all()->first(fn ($s) => $s->getTranslation('title', 'en', false) === 'Old Slide'));
    }
}

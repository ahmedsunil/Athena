<?php

namespace Tests\Feature;

use App\Models\HomeQuickAccess;
use App\Models\HomeStat;
use App\Models\HomeTestimonial;
use App\Models\SchoolProfile;
use Database\Seeders\HomeQuickAccessSeeder;
use Database\Seeders\HomeStatSeeder;
use Database\Seeders\HomeTestimonialSeeder;
use Database\Seeders\SchoolProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageSeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_supporting_data_is_seeded_from_public_home_json(): void
    {
        $this->seed([
            SchoolProfileSeeder::class,
            HomeStatSeeder::class,
            HomeQuickAccessSeeder::class,
            HomeTestimonialSeeder::class,
        ]);

        $this->assertDatabaseHas('school_profiles', [
            'id' => 1,
            'principal_name' => 'Dr. Patricia Nwachukwu',
            'email' => 'info@schoolportal.edu.ng',
        ]);

        $this->assertDatabaseHas('home_stats', [
            'title' => 'Students Enrolled',
            'value' => '1,200+',
            'sort_order' => 0,
        ]);

        $this->assertDatabaseHas('home_quick_access', [
            'title' => 'Admissions',
            'icon_key' => 'ClipboardList',
            'link_key' => '/admissions',
        ]);

        $this->assertDatabaseHas('home_testimonials', [
            'name' => 'Mrs. Adaeze Okonkwo',
            'current_designation' => 'Parent of Year 9 Student',
        ]);

        $this->assertSame(4, HomeStat::count());
        $this->assertSame(8, HomeQuickAccess::count());
        $this->assertSame(4, HomeTestimonial::count());

        $profile = SchoolProfile::firstOrFail();
        $testimonial = HomeTestimonial::where('name', 'Mrs. Adaeze Okonkwo')->firstOrFail();

        $this->assertSame('https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80', $profile->principal_photo_url);
        $this->assertSame('https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=200&q=80', $testimonial->photo_url);
    }

    public function test_home_page_supporting_seeders_are_idempotent(): void
    {
        $seeders = [
            SchoolProfileSeeder::class,
            HomeStatSeeder::class,
            HomeQuickAccessSeeder::class,
            HomeTestimonialSeeder::class,
        ];

        $this->seed($seeders);
        $this->seed($seeders);

        $this->assertSame(1, SchoolProfile::count());
        $this->assertSame(4, HomeStat::count());
        $this->assertSame(8, HomeQuickAccess::count());
        $this->assertSame(4, HomeTestimonial::count());
    }
}

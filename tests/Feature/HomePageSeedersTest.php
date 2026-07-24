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

        $profile = SchoolProfile::firstOrFail();
        $this->assertSame(1, $profile->id);
        $this->assertSame('Mr. Mohamed Rasheed', $profile->getTranslation('principal_name', 'en', false));
        $this->assertSame('info@hulhudhuffaaruschool.edu.mv', $profile->email);
        $this->assertSame(
            'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80',
            $profile->principal_photo_url
        );

        $stat = HomeStat::all()->first(fn ($s) => $s->getTranslation('title', 'en', false) === 'Students Enrolled');
        $this->assertNotNull($stat);
        $this->assertSame('850+', $stat->value);
        $this->assertSame(0, $stat->sort_order);

        $qa = HomeQuickAccess::all()->first(fn ($q) => $q->getTranslation('title', 'en', false) === 'Admissions');
        $this->assertNotNull($qa);
        $this->assertSame('ClipboardList', $qa->icon_key);
        $this->assertSame('/admissions', $qa->link_key);

        $testimonial = HomeTestimonial::all()->first(fn ($t) => $t->name === 'Mrs. Aminath Shazna');
        $this->assertNotNull($testimonial);
        $this->assertSame('Parent of Grade 7 Student', $testimonial->getTranslation('current_designation', 'en', false));
        $this->assertSame(
            'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=200&q=80',
            $testimonial->photo_url
        );

        $this->assertSame(4, HomeStat::count());
        $this->assertSame(8, HomeQuickAccess::count());
        $this->assertSame(4, HomeTestimonial::count());
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

    public function test_home_page_supporting_seeders_overwrite_existing_rows(): void
    {
        HomeStat::create([
            'title' => ['en' => 'Old Stat'],
            'value' => '0',
            'is_active' => true,
            'sort_order' => 99,
        ]);

        HomeQuickAccess::create([
            'title' => ['en' => 'Old Link'],
            'icon_key' => 'Old',
            'link_key' => '/old',
            'is_active' => true,
            'sort_order' => 99,
        ]);

        HomeTestimonial::create([
            'name' => 'Old Person',
            'previous_designation' => ['en' => ''],
            'current_designation' => ['en' => 'Old'],
            'message' => ['en' => 'Old'],
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $this->seed([
            HomeStatSeeder::class,
            HomeQuickAccessSeeder::class,
            HomeTestimonialSeeder::class,
        ]);

        $this->assertSame(4, HomeStat::count());
        $this->assertSame(8, HomeQuickAccess::count());
        $this->assertSame(4, HomeTestimonial::count());
        $this->assertNull(HomeStat::all()->first(fn ($s) => $s->getTranslation('title', 'en', false) === 'Old Stat'));
        $this->assertNull(HomeQuickAccess::all()->first(fn ($q) => $q->getTranslation('title', 'en', false) === 'Old Link'));
        $this->assertNull(HomeTestimonial::where('name', 'Old Person')->first());
    }
}

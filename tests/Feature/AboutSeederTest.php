<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\FoundingMember;
use App\Models\HistorySection;
use App\Models\LeadershipMember;
use App\Models\Mission;
use App\Models\SchoolProfile;
use Database\Seeders\AboutSeeder;
use Database\Seeders\SchoolProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_data_is_seeded_with_english_translations(): void
    {
        $this->seed([
            SchoolProfileSeeder::class,
            AboutSeeder::class,
        ]);

        $profile = SchoolProfile::firstOrFail();
        $this->assertSame('Hulhudhuffaaru School', $profile->getTranslation('school_name', 'en', false));

        $mission = Mission::firstOrFail();
        $this->assertStringContainsString('safe, inclusive', $mission->getTranslation('mission', 'en', false));

        $leader = LeadershipMember::where('name', 'Mr. Mohamed Rasheed')->firstOrFail();
        $this->assertSame('Principal', $leader->getTranslation('role', 'en', false));

        FoundingMember::where('name', 'Mr. Ahmed Niyaz')->firstOrFail();

        $history = HistorySection::orderBy('sort_order')->firstOrFail();
        $this->assertStringContainsString('Great Migration', $history->getTranslation('title', 'en', false));

        Achievement::where('year', 2025)->where('category', 'students')->firstOrFail();
    }

    public function test_about_seeder_overwrites_existing_rows(): void
    {
        LeadershipMember::create([
            'name' => 'Old Leader',
            'role' => ['en' => 'Old'],
            'bio' => ['en' => 'Old'],
            'is_active' => true,
            'sort_order' => 99,
        ]);

        $this->seed(AboutSeeder::class);

        $this->assertSame(4, LeadershipMember::count());
        $this->assertSame(3, FoundingMember::count());
        $this->assertSame(4, HistorySection::count());
        $this->assertSame(5, Achievement::count());
        $this->assertNull(LeadershipMember::where('name', 'Old Leader')->first());
    }
}

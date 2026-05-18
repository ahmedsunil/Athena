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

    public function test_about_data_is_seeded_with_english_and_dhivehi_translations(): void
    {
        $this->seed([
            SchoolProfileSeeder::class,
            AboutSeeder::class,
        ]);

        $profile = SchoolProfile::firstOrFail();
        $this->assertSame('Hulhudhuffaaru School', $profile->getTranslation('school_name', 'en', false));
        $this->assertSame('ހުޅުދުއްފާރު ސްކޫލް', $profile->getTranslation('school_name', 'dv', false));

        $mission = Mission::firstOrFail();
        $this->assertStringContainsString('safe, inclusive', $mission->getTranslation('mission', 'en', false));
        $this->assertStringContainsString('އަމާން', $mission->getTranslation('mission', 'dv', false));

        $leader = LeadershipMember::where('name', 'Mr. Mohamed Rasheed')->firstOrFail();
        $this->assertSame('މުޙައްމަދު ރަޝީދު', $leader->name_dv);
        $this->assertSame('Principal', $leader->getTranslation('role', 'en', false));
        $this->assertSame('ޕްރިންސިޕަލް', $leader->getTranslation('role', 'dv', false));

        $founder = FoundingMember::where('name', 'Mr. Ahmed Niyaz')->firstOrFail();
        $this->assertSame('އަޙްމަދު ނިޔާޒް', $founder->name_dv);
        $this->assertSame('މެތުމެޓިކްސް އަދި ދިވެހި', $founder->getTranslation('subject', 'dv', false));

        $history = HistorySection::orderBy('sort_order')->firstOrFail();
        $this->assertStringContainsString('Great Migration', $history->getTranslation('title', 'en', false));
        $this->assertStringContainsString('ބޮޑު ނަގާލުން', $history->getTranslation('title', 'dv', false));

        $achievement = Achievement::where('year', 2025)->where('category', 'students')->firstOrFail();
        $this->assertSame('އަމީނަތު ރިފާ', $achievement->person_name_dv);
        $this->assertSame('1 ވަނަ', $achievement->getTranslation('award', 'dv', false));
    }

    public function test_about_seeder_overwrites_existing_rows(): void
    {
        LeadershipMember::create([
            'name' => 'Old Leader',
            'role' => ['en' => 'Old', 'dv' => 'ކުރީގެ'],
            'bio' => ['en' => 'Old', 'dv' => 'ކުރީގެ'],
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

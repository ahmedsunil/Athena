<?php

namespace Tests\Feature;

use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DigitalServicesModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_digital_services_page_returns_ok(): void
    {
        $response = $this->get('/digital-services');
        $response->assertOk();
    }

    public function test_cms_documents_requires_auth(): void
    {
        $this->get('/cms/digital-services/documents')->assertRedirect('/login');
    }

    public function test_cms_resources_requires_auth(): void
    {
        $this->get('/cms/digital-services/resources')->assertRedirect('/login');
    }

    public function test_cms_calendar_requires_auth(): void
    {
        $this->get('/cms/digital-services/calendar')->assertRedirect('/login');
    }

    public function test_cms_calendar_lists_calendars_before_entries(): void
    {
        $calendar = DigitalServiceCalendar::create([
            'title'       => 'Academic Calendar 2026',
            'year'        => 2026,
            'description' => 'School year overview',
            'is_active'   => true,
        ]);

        DigitalServiceCalendarEntry::factory()->create([
            'calendar_id' => $calendar->id,
            'title'       => 'First Term Begins',
            'date'        => '2026-01-11',
            'is_active'   => true,
        ]);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($admin);

        $this->actingAs($user)
            ->get('/cms/digital-services/calendar')
            ->assertOk()
            ->assertSee('Academic Calendar 2026')
            ->assertSee('Generate from MOE')
            ->assertDontSee('First Term Begins');

        $this->actingAs($user)
            ->get('/cms/digital-services/calendar?calendarId=' . $calendar->id)
            ->assertOk()
            ->assertSee('Academic Calendar 2026')
            ->assertSee('Generate from MOE')
            ->assertSee('First Term Begins');
    }

    public function test_moe_academic_calendar_command_imports_json_into_database(): void
    {
        $inputPath = storage_path('framework/testing/moe-academic-calendar-input.json');
        $outputPath = storage_path('framework/testing/moe-academic-calendar-output.json');

        File::ensureDirectoryExists(dirname($inputPath));

        File::put($inputPath, json_encode([
            'source_url' => 'https://moe.gov.mv/en/academic-calendar',
            'scraped_at' => '2026-05-20T00:00:00Z',
            'available_years' => [2026],
            'years' => [
                [
                    'year' => 2026,
                    'event_count' => 3,
                    'events' => [
                        [
                            'id' => '1',
                            'year' => 2026,
                            'title_en' => 'Beginning of Academic Year 2026',
                            'event_type' => 'Academic',
                            'start_date' => '2026-01-27',
                            'end_date' => null,
                            'description_en' => 'First day of Term 1.',
                            'is_tentative' => false,
                        ],
                        [
                            'id' => '2',
                            'year' => 2026,
                            'title_en' => 'Mid-Term Break',
                            'event_type' => 'Holiday',
                            'start_date' => '2026-02-18',
                            'end_date' => '2026-02-22',
                            'description_en' => 'School closed for the break.',
                            'is_tentative' => true,
                        ],
                        [
                            'id' => '3',
                            'year' => 2026,
                            'title_en' => 'First Term Examinations',
                            'event_type' => 'Exam',
                            'start_date' => '2026-03-15',
                            'end_date' => '2026-03-19',
                            'description_en' => 'End-of-term examinations.',
                            'is_tentative' => false,
                        ],
                    ],
                ],
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        $exitCode = Artisan::call('moe:academic-calendar', [
            '--input' => $inputPath,
            '--output' => $outputPath,
        ]);

        $this->assertSame(0, $exitCode);

        $calendar = DigitalServiceCalendar::where('year', 2026)->firstOrFail();
        $this->assertSame('Academic Calendar 2026', $calendar->title);
        $this->assertTrue($calendar->is_active);
        $this->assertStringContainsString('Imported from MOE academic calendar.', $calendar->description ?? '');

        $this->assertDatabaseCount('digital_service_calendars', 1);
        $this->assertDatabaseCount('digital_service_calendar_entries', 3);
        $this->assertDatabaseHas('digital_service_calendar_entries', [
            'calendar_id' => $calendar->id,
            'title' => 'Beginning of Academic Year 2026',
            'type' => 'term',
            'date' => '2026-01-27 00:00:00',
            'is_tentative' => 0,
        ]);
        $this->assertDatabaseHas('digital_service_calendar_entries', [
            'calendar_id' => $calendar->id,
            'title' => 'Mid-Term Break',
            'type' => 'holiday',
            'is_tentative' => 1,
        ]);
        $this->assertFileExists($outputPath);
    }

    public function test_downloads_tab_shows_active_document(): void
    {
        DigitalServiceDocument::factory()->create([
            'title'        => 'Student Handbook',
            'category'     => 'Policies & Handbooks',
            'file_type'    => 'PDF',
            'audience'     => 'Students',
            'published_at' => '2024-08-15',
            'is_active'    => true,
        ]);

        $response = $this->get('/digital-services');
        $response->assertSee('Student Handbook');
    }

    public function test_downloads_tab_hides_inactive_document(): void
    {
        DigitalServiceDocument::factory()->create([
            'title'        => 'Hidden Document',
            'is_active'    => false,
            'published_at' => '2024-08-15',
        ]);

        $response = $this->get('/digital-services');
        $response->assertDontSee('Hidden Document');
    }

    public function test_resources_tab_shows_active_resource(): void
    {
        DigitalServiceResource::factory()->create([
            'title'     => 'Google Classroom',
            'audience'  => 'Students',
            'is_active' => true,
        ]);

        $response = $this->get('/digital-services?activeTab=resources');
        $response->assertSee('Google Classroom');
    }

    public function test_resources_tab_hides_inactive_resource(): void
    {
        DigitalServiceResource::factory()->create([
            'title'     => 'Hidden Resource',
            'is_active' => false,
        ]);

        $response = $this->get('/digital-services?activeTab=resources');
        $response->assertDontSee('Hidden Resource');
    }

    public function test_calendar_tab_shows_active_entry(): void
    {
        $calendar = DigitalServiceCalendar::create([
            'title'     => 'Academic Calendar 2025',
            'year'      => 2025,
            'is_active' => true,
        ]);

        DigitalServiceCalendarEntry::factory()->create([
            'calendar_id' => $calendar->id,
            'title'       => 'Sports Day',
            'type'        => 'event',
            'date'        => '2025-05-16',
            'is_active'   => true,
        ]);

        // calendarMonth=4 = May (0-indexed from Jan)
        $response = $this->get('/digital-services?activeTab=calendar&calendarMonth=4');
        $response->assertSee('Sports Day');
    }

    public function test_documents_filter_by_category(): void
    {
        DigitalServiceDocument::factory()->create([
            'title'        => 'Enrolment Form',
            'category'     => 'Forms & Applications',
            'is_active'    => true,
            'published_at' => '2024-08-01',
        ]);
        DigitalServiceDocument::factory()->create([
            'title'        => 'Student Handbook',
            'category'     => 'Policies & Handbooks',
            'is_active'    => true,
            'published_at' => '2024-08-15',
        ]);

        $response = $this->get('/digital-services?activeCategory=Forms+%26+Applications');
        $response->assertSee('Enrolment Form');
        $response->assertDontSee('Student Handbook');
    }
}

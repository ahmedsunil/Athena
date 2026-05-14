<?php

namespace Tests\Feature;

use App\Models\DigitalServiceDocument;
use App\Models\DigitalServiceResource;
use App\Models\DigitalServiceCalendarEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        DigitalServiceCalendarEntry::factory()->create([
            'title'     => 'Sports Day',
            'type'      => 'event',
            'date'      => '2025-05-16',
            'is_active' => true,
        ]);

        $response = $this->get('/digital-services?activeTab=calendar');
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

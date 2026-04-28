<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\HomePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_api_uses_featured_events_from_event_records(): void
    {
        HomePage::create([
            'payload' => json_encode([
                'slides' => [],
                'stats' => [],
                'principal' => [],
                'quickLinks' => [],
                'testimonials' => [],
                'contact' => [],
                'featuredEvents' => [
                    ['id' => 'old-event'],
                ],
            ], JSON_THROW_ON_ERROR),
        ]);

        $this->createEvent([
            'public_id' => 'evt-001',
            'title' => 'Open Day May 2025',
            'slug' => 'open-day-2025',
            'date_start' => '2025-05-10',
            'short_description' => 'Tour the school.',
            'is_featured' => true,
            'featured_sort_order' => 0,
        ]);

        $this->createEvent([
            'public_id' => 'evt-002',
            'title' => 'Sports Day',
            'slug' => 'sports-day',
            'date_start' => '2025-05-24',
            'short_description' => 'Inter-house sports.',
            'is_featured' => false,
        ]);

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonPath('featuredEvents.0.id', 'evt-001')
            ->assertJsonPath('featuredEvents.0.href', '/events/open-day-2025')
            ->assertJsonMissingPath('featuredEvents.1');
    }

    public function test_home_api_falls_back_when_stored_payload_is_invalid_json(): void
    {
        HomePage::create(['payload' => '{"slides": [']);

        $this->createEvent([
            'public_id' => 'evt-003',
            'title' => 'Prize Giving',
            'slug' => 'prize-giving-2025',
            'date_start' => '2025-07-04',
            'short_description' => 'Celebrating student achievement.',
            'is_featured' => true,
            'featured_sort_order' => 0,
        ]);

        $this->getJson('/api/home')
            ->assertOk()
            ->assertJsonPath('featuredEvents.0.id', 'evt-003')
            ->assertJsonPath('featuredEvents.0.href', '/events/prize-giving-2025')
            ->assertJsonStructure(['slides', 'stats', 'principal', 'featuredEvents']);
    }

    private function createEvent(array $overrides = []): Event
    {
        return Event::create(array_merge([
            'public_id' => 'evt-test',
            'status' => 'upcoming',
            'title' => 'Test Event',
            'slug' => 'test-event',
            'date_start' => '2025-01-01',
            'date_end' => '2025-01-01',
            'location' => 'School Campus',
            'cover_image_url' => null,
            'short_description' => 'Short description.',
            'full_description' => 'Full description.',
            'attachments' => [],
            'contact' => null,
            'is_featured' => false,
            'featured_sort_order' => 0,
        ], $overrides));
    }
}

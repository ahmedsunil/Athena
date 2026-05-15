<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_page_returns_ok(): void
    {
        $this->get('/events')->assertOk();
    }

    public function test_cms_events_route_requires_auth(): void
    {
        $this->get('/cms/events')->assertRedirect('/login');
    }

    public function test_events_page_shows_active_events(): void
    {
        Event::create([
            'title'             => 'Science Fair',
            'slug'              => 'science-fair',
            'status'            => 'upcoming',
            'date_start'        => '2025-06-01',
            'location'          => 'Main Hall',
            'short_description' => 'A science exhibition.',
            'is_active'         => true,
        ]);

        $this->get('/events')->assertSee('Science Fair');
    }

    public function test_events_page_hides_inactive_events(): void
    {
        Event::create([
            'title'             => 'Hidden Event',
            'slug'              => 'hidden-event',
            'status'            => 'upcoming',
            'date_start'        => '2025-06-01',
            'location'          => 'Main Hall',
            'short_description' => 'Not shown.',
            'is_active'         => false,
        ]);

        $this->get('/events')->assertDontSee('Hidden Event');
    }

    public function test_home_page_shows_featured_events(): void
    {
        Event::create([
            'title'               => 'Open Day',
            'slug'                => 'open-day',
            'status'              => 'upcoming',
            'date_start'          => '2025-05-10',
            'location'            => 'Campus',
            'short_description'   => 'Prospective families welcome.',
            'is_featured'         => true,
            'featured_sort_order' => 0,
            'is_active'           => true,
        ]);

        $this->get('/')->assertSee('Open Day');
    }

    public function test_home_page_hides_featured_section_when_no_featured_events(): void
    {
        // The translation key value appears in the embedded JS translations on every page.
        // We check that the featured-events section heading is not rendered in the HTML body.
        $response = $this->get('/');
        $html = $response->content();
        $this->assertStringNotContainsString(
            '<h2 class="text-2xl sm:text-3xl font-black text-slate-900">Featured Events</h2>',
            $html,
            'Featured Events heading should not appear when no featured events exist.'
        );
    }
}

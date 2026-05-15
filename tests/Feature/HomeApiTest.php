<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_site_pages_render_from_the_site_layer(): void
    {
        foreach (['/', '/about', '/academics', '/admissions', '/events', '/student-life', '/gallery', '/digital-services'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_event_detail_page_renders(): void
    {
        Event::create([
            'title'             => 'Open Day May 2025',
            'slug'              => 'open-day-may-2025',
            'status'            => 'completed',
            'date_start'        => '2025-05-15',
            'location'          => 'School Hall',
            'short_description' => 'Annual open day event',
            'is_active'         => true,
        ]);

        $this->get('/events/open-day-may-2025')
            ->assertOk()
            ->assertSee('Open Day May 2025');
    }
}

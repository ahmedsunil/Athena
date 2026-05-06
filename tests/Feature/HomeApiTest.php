<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeApiTest extends TestCase
{
    public function test_public_site_pages_render_from_the_site_layer(): void
    {
        foreach (['/', '/about', '/academics', '/admissions', '/events', '/student-life', '/gallery', '/downloads', '/digital-services', '/search'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_event_detail_page_renders_from_static_site_data(): void
    {
        $this->get('/events/open-day-may-2025')
            ->assertOk()
            ->assertSee('const id = "evt-002"', false);
    }
}

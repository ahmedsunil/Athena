<?php

namespace Tests\Feature;

use App\Models\Event;
use Database\Seeders\SchoolProfileSeeder;
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

    public function test_public_profile_locations_do_not_render_translation_json(): void
    {
        $this->seed(SchoolProfileSeeder::class);

        $this->get('/')
            ->assertOk()
            ->assertSee('Hulhudhuffaaru, Raa Atoll, Maldives')
            ->assertDontSee('{"en":"Hulhudhuffaaru"', false)
            ->assertDontSee('{&quot;en&quot;:&quot;Hulhudhuffaaru&quot;', false);

        $this->get('/about')
            ->assertOk()
            ->assertSee('Hulhudhuffaaru, Raa Atoll')
            ->assertDontSee('{"en":"Hulhudhuffaaru"', false)
            ->assertDontSee('{&quot;en&quot;:&quot;Hulhudhuffaaru&quot;', false);
    }

}

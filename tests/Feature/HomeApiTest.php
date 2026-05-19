<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Event;
use Database\Seeders\HomeTestimonialSeeder;
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

    public function test_gallery_and_digital_services_filter_pills_render_in_dhivehi(): void
    {
        $this->withSession(['locale' => 'dv'])
            ->get('/gallery')
            ->assertOk()
            ->assertSee('ހަރަކާތްތައް')
            ->assertSee('ކުޅިވަރު')
            ->assertSee('މޭ')
            ->assertDontSee('>Events<', false)
            ->assertDontSee('>Sports<', false)
            ->assertDontSee('>May<', false);

        $this->withSession(['locale' => 'dv'])
            ->get('/digital-services')
            ->assertOk()
            ->assertSee('ފޯމްތަކާއި އެޕްލިކޭޝަންތައް')
            ->assertSee('ޕޮލިސީތަކާއި ހޭންޑްބުކްތައް')
            ->assertSee('ދަރިވަރުން')
            ->assertSee('މޭ')
            ->assertDontSee('>Forms &amp; Applications<', false)
            ->assertDontSee('>Students<', false)
            ->assertDontSee('>May<', false);
    }

    public function test_announcement_dates_render_months_in_dhivehi(): void
    {
        Announcement::forceCreate([
            'sort_order' => 1,
            'icon_key' => 'Bell',
            'category' => ['en' => 'Announcement', 'dv' => 'އިއުލާން'],
            'title' => ['en' => 'Parent Meeting', 'dv' => 'ބަލިވެރިންގެ ބައްދަލުވުން'],
            'slug' => 'parent-meeting',
            'description' => ['en' => 'Meeting details', 'dv' => 'ބައްދަލުވުމުގެ ތަފްސީލް'],
            'deadline' => '2026-06-01',
            'attachments' => [],
            'is_active' => true,
            'created_at' => '2026-05-10 09:00:00',
            'updated_at' => '2026-05-10 09:00:00',
        ]);

        $this->withSession(['locale' => 'dv'])
            ->get('/announcements')
            ->assertOk()
            ->assertSee('10 މޭ 2026')
            ->assertSee('1 ޖޫން 2026')
            ->assertDontSee('10 May 2026')
            ->assertDontSee('1 Jun 2026');

        $this->withSession(['locale' => 'dv'])
            ->get('/announcements/parent-meeting')
            ->assertOk()
            ->assertSee('10 މޭ 2026')
            ->assertSee('1 ޖޫން 2026')
            ->assertDontSee('10 May 2026')
            ->assertDontSee('1 Jun 2026');
    }

    public function test_event_status_labels_render_in_dhivehi(): void
    {
        foreach (['upcoming', 'ongoing', 'completed'] as $index => $status) {
            Event::create([
                'title' => ucfirst($status) . ' Event',
                'slug' => $status . '-event',
                'status' => $status,
                'date_start' => now()->addDays($index + 1)->toDateString(),
                'location' => 'School Hall',
                'short_description' => 'Event details',
                'is_active' => true,
            ]);
        }

        $this->withSession(['locale' => 'dv'])
            ->get('/events')
            ->assertOk()
            ->assertSee('ކުރިއަށް އޮތް')
            ->assertSee('ކުރިއަށްދާ')
            ->assertSee('ނިމިފައި')
            ->assertDontSee('>Upcoming<', false)
            ->assertDontSee('>Ongoing<', false)
            ->assertDontSee('>Completed<', false);

        $this->withSession(['locale' => 'dv'])
            ->get('/events/upcoming-event')
            ->assertOk()
            ->assertSee('ކުރިއަށް އޮތް')
            ->assertDontSee('>Upcoming<', false);
    }

    public function test_about_achievement_category_pills_render_in_dhivehi(): void
    {
        $this->withSession(['locale' => 'dv'])
            ->get('/about')
            ->assertOk()
            ->assertSee('ދަރިވަރުން')
            ->assertSee('ސްޓާފް')
            ->assertSee('ސްކޫލް')
            ->assertDontSee('>Students<', false)
            ->assertDontSee('>Staff<', false)
            ->assertDontSee('>School<', false);
    }

    public function test_home_testimonial_names_render_in_dhivehi(): void
    {
        $this->seed(HomeTestimonialSeeder::class);

        $this->withSession(['locale' => 'dv'])
            ->get('/')
            ->assertOk()
            ->assertSee('އަމީނަތު ޝަޒްނާ')
            ->assertDontSee('Mrs. Aminath Shazna');
    }
}

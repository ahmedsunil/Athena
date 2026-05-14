<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\HomeQuickAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_announcements_page_shows_active_items_by_default(): void
    {
        Announcement::create([
            'title' => 'Active Bid',
            'slug' => 'active-bid',
            'category' => 'Bid',
            'deadline' => now()->addWeek()->toDateString(),
            'is_active' => true,
        ]);

        Announcement::create([
            'title' => 'Closed Job',
            'slug' => 'closed-job',
            'category' => 'Job Opening',
            'deadline' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        $this->get('/announcements')
            ->assertOk()
            ->assertSee('Active Bid')
            ->assertDontSee('Closed Job');
    }

    public function test_announcements_closed_tab_shows_expired_items(): void
    {
        Announcement::create([
            'title' => 'Closed Bid',
            'slug' => 'closed-bid',
            'category' => 'Bid',
            'deadline' => now()->subDay()->toDateString(),
            'is_active' => true,
        ]);

        Announcement::create([
            'title' => 'Inactive Notice',
            'slug' => 'inactive-notice',
            'category' => 'Notice',
            'deadline' => now()->addWeek()->toDateString(),
            'is_active' => false,
        ]);

        $this->get('/announcements?filter=closed')
            ->assertOk()
            ->assertSee('Closed Bid')
            ->assertSee('Inactive Notice');
    }

    public function test_announcement_detail_page_renders(): void
    {
        Announcement::create([
            'title' => 'Public Competition',
            'slug' => 'public-competition',
            'category' => 'Competition',
            'description' => 'Competition details',
            'is_active' => true,
        ]);

        $this->get('/announcements/public-competition')
            ->assertOk()
            ->assertSee('Public Competition')
            ->assertSee('Competition details');
    }

    public function test_inactive_closed_announcement_detail_page_renders(): void
    {
        Announcement::create([
            'title' => 'Closed Notice',
            'slug' => 'closed-notice',
            'category' => 'Notice',
            'description' => 'Closed details',
            'is_active' => false,
        ]);

        $this->get('/announcements/closed-notice')
            ->assertOk()
            ->assertSee('Closed Notice')
            ->assertSee('Closed details');
    }

    public function test_home_quick_access_replaces_admissions_with_announcements(): void
    {
        HomeQuickAccess::create([
            'icon_key' => 'ClipboardList',
            'title' => 'Admissions',
            'link_key' => '/admissions',
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Announcements')
            ->assertSee('/announcements')
            ->assertDontSee('>Admissions<', false);
    }
}

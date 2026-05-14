<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_gallery_page_returns_ok(): void
    {
        $this->get('/gallery')->assertOk();
    }

    public function test_cms_gallery_requires_auth(): void
    {
        $this->get('/cms/gallery')->assertRedirect('/login');
    }

    public function test_gallery_page_shows_active_album(): void
    {
        GalleryAlbum::create([
            'title'       => 'Test Album',
            'category'    => 'Events',
            'date'        => '2024-06-01',
            'photo_count' => 10,
            'is_active'   => true,
        ]);

        $this->get('/gallery')->assertSee('Test Album');
    }

    public function test_gallery_page_hides_inactive_album(): void
    {
        GalleryAlbum::create([
            'title'       => 'Hidden Album',
            'category'    => 'Sports',
            'date'        => '2024-06-01',
            'photo_count' => 5,
            'is_active'   => false,
        ]);

        $this->get('/gallery')->assertDontSee('Hidden Album');
    }

    public function test_gallery_filters_by_category(): void
    {
        GalleryAlbum::create([
            'title'       => 'Sports Event',
            'category'    => 'Sports',
            'date'        => '2024-06-01',
            'photo_count' => 50,
            'is_active'   => true,
        ]);

        GalleryAlbum::create([
            'title'       => 'Drama Show',
            'category'    => 'Cultural',
            'date'        => '2024-06-01',
            'photo_count' => 30,
            'is_active'   => true,
        ]);

        $this->get('/gallery?activeCategory=Sports')
             ->assertSee('Sports Event')
             ->assertDontSee('Drama Show');
    }
}

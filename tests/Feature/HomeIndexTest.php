<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_cms_home_route_remains_separate_from_public_home(): void
    {
        $this->get('/cms/school-profile')->assertRedirect('/login');
        $this->get('/')->assertOk();
    }
}

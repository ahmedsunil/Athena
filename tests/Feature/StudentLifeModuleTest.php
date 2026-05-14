<?php

namespace Tests\Feature;

use App\Models\StudentLifeClub;
use App\Models\StudentLifeHouse;
use App\Models\StudentLifePrefect;
use App\Models\StudentLifeUniformBody;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLifeModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_student_life_page_returns_ok(): void
    {
        $this->get('/student-life')->assertOk();
    }

    public function test_cms_clubs_requires_auth(): void
    {
        $this->get('/cms/student-life/clubs')->assertRedirect('/login');
    }

    public function test_cms_prefects_requires_auth(): void
    {
        $this->get('/cms/student-life/prefects')->assertRedirect('/login');
    }

    public function test_cms_houses_requires_auth(): void
    {
        $this->get('/cms/student-life/houses')->assertRedirect('/login');
    }

    public function test_cms_uniform_bodies_requires_auth(): void
    {
        $this->get('/cms/student-life/uniform-bodies')->assertRedirect('/login');
    }

    public function test_student_life_page_shows_active_club(): void
    {
        StudentLifeClub::create([
            'name'      => 'Science Club',
            'is_active' => true,
        ]);

        $this->get('/student-life')->assertSee('Science Club');
    }

    public function test_student_life_page_hides_inactive_club(): void
    {
        StudentLifeClub::create([
            'name'      => 'Hidden Club',
            'is_active' => false,
        ]);

        $this->get('/student-life')->assertDontSee('Hidden Club');
    }

    public function test_student_life_page_shows_active_house(): void
    {
        StudentLifeHouse::create([
            'name'      => 'Phoenix House',
            'colour'    => 'rose',
            'is_active' => true,
        ]);

        $this->get('/student-life?activeTab=houses')->assertSee('Phoenix House');
    }

    public function test_student_life_page_shows_active_prefect(): void
    {
        StudentLifePrefect::create([
            'name'      => 'Test Prefect',
            'role'      => 'Head Boy',
            'is_active' => true,
        ]);

        $this->get('/student-life?activeTab=prefects')->assertSee('Test Prefect');
    }

    public function test_student_life_page_shows_active_uniform_body(): void
    {
        StudentLifeUniformBody::create([
            'name'       => 'Test Scouts',
            'group_type' => 'Boy Scouts',
            'colour'     => 'emerald',
            'is_active'  => true,
        ]);

        $this->get('/student-life?activeTab=uniform-bodies')->assertSee('Test Scouts');
    }
}

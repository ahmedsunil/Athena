<?php

namespace Tests\Feature;

use App\Models\AcademicLevel;
use App\Models\AcademicsOverview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_academics_page_returns_ok(): void
    {
        $this->get('/academics')->assertOk();
    }

    public function test_cms_academics_overview_requires_auth(): void
    {
        $this->get('/cms/academics/overview')->assertRedirect('/login');
    }

    public function test_cms_academics_levels_requires_auth(): void
    {
        $this->get('/cms/academics/levels')->assertRedirect('/login');
    }

    public function test_academics_page_shows_active_levels(): void
    {
        AcademicLevel::create([
            'abbreviation' => 'KS1',
            'label'        => 'Key Stage 1',
            'age_range'    => 'Ages 6 – 8',
            'year_groups'  => 'Grade 1 – Grade 3',
            'lead_teacher' => 'Ms. Test Teacher',
            'is_active'    => true,
        ]);

        $this->get('/academics')->assertSee('Key Stage 1');
    }

    public function test_academics_page_hides_inactive_levels(): void
    {
        AcademicLevel::create([
            'abbreviation' => 'KS2',
            'label'        => 'Key Stage 2',
            'age_range'    => 'Ages 9 – 11',
            'year_groups'  => 'Grade 4 – Grade 6',
            'lead_teacher' => 'Mr. Hidden',
            'is_active'    => false,
        ]);

        $this->get('/academics')->assertDontSee('Key Stage 2');
    }

    public function test_academics_page_shows_overview_text(): void
    {
        AcademicsOverview::create([
            'id'         => 1,
            'text'       => 'World-class education.',
            'curriculum' => 'Cambridge · Pearson Edexcel',
        ]);

        $this->get('/academics')->assertSee('World-class education.');
    }
}

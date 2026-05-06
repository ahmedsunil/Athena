<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_returns_a_successful_response(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Hulhudhuffaaru School');
    }
}

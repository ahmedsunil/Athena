<?php

namespace Tests\Feature;

use App\Livewire\Cms\Home\HomeIndex;
use Tests\TestCase;

class HomeIndexTest extends TestCase
{
    public function test_generated_ids_do_not_reuse_deleted_item_suffixes(): void
    {
        $component = new HomeIndex;
        $component->isEditing = true;
        $component->slides = [
            ['id' => 'slide-1'],
            ['id' => 'slide-3'],
        ];

        $component->addSlide();

        $this->assertSame('slide-4', $component->slides[2]['id']);
    }
}

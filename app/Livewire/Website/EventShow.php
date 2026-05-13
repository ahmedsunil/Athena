<?php

namespace App\Livewire\Website;

use Livewire\Component;

class EventShow extends Component
{
    public string $slug = '';

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('livewire.website.event-show');
    }
}

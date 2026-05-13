<?php

namespace App\Livewire\Website;

use App\Models\Event;
use Livewire\Component;

class EventShow extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render()
    {
        $event = Event::where('slug', $this->slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('livewire.website.event-show', [
            'event' => $event,
        ])->layout('layouts.web');
    }
}

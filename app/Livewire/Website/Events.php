<?php

namespace App\Livewire\Website;

use App\Models\Event;
use Livewire\Component;

class Events extends Component
{
    public string $filter = 'all';

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function render()
    {
        $events = Event::where('is_active', true)
            ->when($this->filter !== 'all', fn ($q) => $q->where('status', $this->filter))
            ->orderByRaw("CASE WHEN status='ongoing' THEN 0 WHEN status='upcoming' THEN 1 ELSE 2 END")
            ->orderBy('date_start')
            ->get();

        return view('livewire.website.events', [
            'events' => $events,
        ])->layout('layouts.web');
    }
}

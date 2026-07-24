<?php

namespace App\Livewire\Cms\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Event deleted.');
    }

    public function render()
    {
        return view('livewire.cms.events.events-index', [
            'events' => Event::orderByDesc('date_start')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Events']);
    }
}

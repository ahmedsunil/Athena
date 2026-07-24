<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use Livewire\Component;
use Livewire\WithPagination;

class CalendarsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        DigitalServiceCalendar::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Calendar deleted.');
    }

    public function render()
    {
        return view('livewire.cms.digital-services.calendars-index', [
            'calendars' => DigitalServiceCalendar::withCount('entries')->orderBy('sort_order')->orderBy('year', 'desc')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Media — Calendars']);
    }
}

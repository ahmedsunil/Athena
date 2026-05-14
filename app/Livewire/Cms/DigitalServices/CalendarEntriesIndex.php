<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use App\Models\DigitalServiceCalendarEntry;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CalendarEntriesIndex extends Component
{
    use WithPagination;

    #[Url]
    public ?int $calendarId = null;

    public string $title = '';
    public string $date = '';
    public string $end_date = '';
    public string $type = 'event';
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    public array $types = ['event', 'term', 'holiday', 'exam'];

    public function mount(): void
    {
        if ($this->calendarId === null) {
            $active = DigitalServiceCalendar::where('is_active', true)->first()
                   ?? DigitalServiceCalendar::orderBy('year', 'desc')->first();
            $this->calendarId = $active?->id;
        }
    }

    public function updatedCalendarId(): void
    {
        $this->resetPage();
        $this->resetForm();
    }

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['required', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:date'],
            'type'        => ['required', 'in:event,term,holiday,exam'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'calendar_id' => $this->calendarId,
            'title'       => $this->title,
            'date'        => $this->date,
            'end_date'    => $this->end_date ?: null,
            'type'        => $this->type,
            'description' => $this->description ?: null,
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];

        if ($this->editingId) {
            DigitalServiceCalendarEntry::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Entry updated.');
        } else {
            DigitalServiceCalendarEntry::create($data);
            $this->dispatch('toast', message: 'Entry added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $entry = DigitalServiceCalendarEntry::findOrFail($id);
        $this->editingId   = $entry->id;
        $this->title       = $entry->title;
        $this->date        = $entry->date->format('Y-m-d');
        $this->end_date    = $entry->end_date?->format('Y-m-d') ?? '';
        $this->type        = $entry->type;
        $this->description = $entry->description ?? '';
        $this->sort_order  = $entry->sort_order;
        $this->is_active   = $entry->is_active;
    }

    public function delete(int $id): void
    {
        DigitalServiceCalendarEntry::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Entry deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'date', 'end_date', 'description', 'editingId']);
        $this->type       = 'event';
        $this->is_active  = true;
        $this->sort_order = 0;
    }

    public function render()
    {
        $calendars = DigitalServiceCalendar::orderBy('year', 'desc')->get();
        $currentCalendar = $this->calendarId
            ? DigitalServiceCalendar::find($this->calendarId)
            : null;

        $entries = $this->calendarId
            ? DigitalServiceCalendarEntry::where('calendar_id', $this->calendarId)
                ->orderBy('date')->orderBy('id')
                ->paginate(15)
            : collect()->paginate(15);

        return view('livewire.cms.digital-services.calendar-entries-index', [
            'calendars'       => $calendars,
            'currentCalendar' => $currentCalendar,
            'entries'         => $entries,
        ])->layout('layouts.app', ['title' => 'Digital Services — Academic Calendar']);
    }
}

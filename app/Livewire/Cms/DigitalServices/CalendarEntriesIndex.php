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

    public string $calendarTitle = '';
    public int $calendarYear = 2026;
    public string $calendarDescription = '';
    public int $calendarSortOrder = 0;
    public bool $calendarIsActive = false;
    public ?int $calendarEditingId = null;
    public bool $showCalendarForm = false;

    public string $title = '';
    public string $date = '';
    public string $end_date = '';
    public string $type = 'event';
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;
    public bool $showEntryForm = false;

    public array $types = ['event', 'term', 'holiday', 'exam'];

    public function selectCalendar(int $id): void
    {
        $this->calendarId = $id;
        $this->showCalendarForm = false;
        $this->resetCalendarForm();
        $this->resetForm();
        $this->resetPage();
    }

    public function backToCalendars(): void
    {
        $this->calendarId = null;
        $this->showEntryForm = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function newCalendar(): void
    {
        $this->resetCalendarForm();
        $this->showCalendarForm = true;
    }

    public function editCalendar(int $id): void
    {
        $calendar = DigitalServiceCalendar::findOrFail($id);

        $this->calendarEditingId = $calendar->id;
        $this->calendarTitle = $calendar->title;
        $this->calendarYear = $calendar->year ?? (int) date('Y');
        $this->calendarDescription = $calendar->description ?? '';
        $this->calendarSortOrder = $calendar->sort_order;
        $this->calendarIsActive = $calendar->is_active;
        $this->showCalendarForm = true;
    }

    public function saveCalendar(): void
    {
        $this->validate($this->calendarRules());

        if ($this->calendarIsActive) {
            DigitalServiceCalendar::where('id', '!=', $this->calendarEditingId ?? 0)->update(['is_active' => false]);
        }

        $data = [
            'title' => $this->calendarTitle,
            'year' => $this->calendarYear,
            'description' => $this->calendarDescription ?: null,
            'sort_order' => $this->calendarSortOrder,
            'is_active' => $this->calendarIsActive,
        ];

        if ($this->calendarEditingId) {
            DigitalServiceCalendar::findOrFail($this->calendarEditingId)->update($data);
            $this->dispatch('toast', message: 'Calendar updated.');
        } else {
            $calendar = DigitalServiceCalendar::create($data);
            $this->calendarId = $calendar->id;
            $this->dispatch('toast', message: 'Calendar added.');
        }

        $this->resetCalendarForm();
    }

    public function deleteCalendar(int $id): void
    {
        DigitalServiceCalendar::findOrFail($id)->delete();

        if ($this->calendarId === $id) {
            $this->backToCalendars();
        }

        $this->dispatch('toast', message: 'Calendar deleted.');
    }

    public function cancelCalendar(): void
    {
        $this->resetCalendarForm();
    }

    public function newEntry(): void
    {
        $this->resetForm();
        $this->showEntryForm = true;
    }

    protected function calendarRules(): array
    {
        return [
            'calendarTitle'       => ['required', 'string', 'max:255'],
            'calendarYear'        => ['required', 'integer', 'min:2000', 'max:2100'],
            'calendarDescription' => ['nullable', 'string'],
            'calendarSortOrder'   => ['integer', 'min:0'],
            'calendarIsActive'    => ['boolean'],
        ];
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
        if (! $this->calendarId) {
            $this->addError('calendarId', 'Select a calendar before adding entries.');
            return;
        }

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
        $this->showEntryForm = true;
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
        $this->showEntryForm = false;
    }

    private function resetCalendarForm(): void
    {
        $this->reset(['calendarTitle', 'calendarDescription', 'calendarEditingId']);
        $this->calendarYear = (int) date('Y');
        $this->calendarSortOrder = 0;
        $this->calendarIsActive = false;
        $this->showCalendarForm = false;
    }

    public function render()
    {
        $calendars = DigitalServiceCalendar::withCount('entries')->orderBy('sort_order')->orderBy('year', 'desc')->get();
        $currentCalendar = $this->calendarId
            ? DigitalServiceCalendar::find($this->calendarId)
            : null;

        $entries = $this->calendarId
            ? DigitalServiceCalendarEntry::where('calendar_id', $this->calendarId)
                ->orderBy('date')->orderBy('id')
                ->paginate(15)
            : DigitalServiceCalendarEntry::whereRaw('1 = 0')->paginate(15);

        return view('livewire.cms.digital-services.calendar-entries-index', [
            'calendars'       => $calendars,
            'currentCalendar' => $currentCalendar,
            'entries'         => $entries,
        ])->layout('layouts.app', ['title' => 'Digital Services — Academic Calendar']);
    }
}

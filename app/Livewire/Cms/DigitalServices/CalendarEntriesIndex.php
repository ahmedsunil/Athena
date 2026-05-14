<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendarEntry;
use Livewire\Component;

class CalendarEntriesIndex extends Component
{
    public string $title = '';
    public string $date = '';
    public string $end_date = '';
    public string $type = 'event';
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    public array $types = ['event', 'term', 'holiday', 'exam'];

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
        return view('livewire.cms.digital-services.calendar-entries-index', [
            'entries' => DigitalServiceCalendarEntry::orderBy('date')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Digital Services — Academic Calendar']);
    }
}

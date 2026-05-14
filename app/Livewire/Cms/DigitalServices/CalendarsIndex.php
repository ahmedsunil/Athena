<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use Livewire\Component;

class CalendarsIndex extends Component
{
    public string $title = '';
    public int $year = 2026;
    public string $description = '';
    public int $sort_order = 0;
    public bool $is_active = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'year'        => ['required', 'integer', 'min:2000', 'max:2100'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['integer', 'min:0'],
            'is_active'   => ['boolean'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->is_active) {
            DigitalServiceCalendar::where('id', '!=', $this->editingId ?? 0)->update(['is_active' => false]);
        }

        $data = [
            'title'       => $this->title,
            'year'        => $this->year,
            'description' => $this->description ?: null,
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];

        if ($this->editingId) {
            DigitalServiceCalendar::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Calendar updated.');
        } else {
            DigitalServiceCalendar::create($data);
            $this->dispatch('toast', message: 'Calendar added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $cal = DigitalServiceCalendar::findOrFail($id);
        $this->editingId   = $cal->id;
        $this->title       = $cal->title;
        $this->year        = $cal->year ?? date('Y');
        $this->description = $cal->description ?? '';
        $this->sort_order  = $cal->sort_order;
        $this->is_active   = $cal->is_active;
    }

    public function delete(int $id): void
    {
        DigitalServiceCalendar::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Calendar deleted.');
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'description', 'editingId']);
        $this->year       = (int) date('Y');
        $this->is_active  = false;
        $this->sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.digital-services.calendars-index', [
            'calendars' => DigitalServiceCalendar::withCount('entries')->orderBy('sort_order')->orderBy('year', 'desc')->get(),
        ])->layout('layouts.app', ['title' => 'Digital Services — Calendars']);
    }
}

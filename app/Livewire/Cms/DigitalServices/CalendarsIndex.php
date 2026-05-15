<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use Livewire\Component;

class CalendarsIndex extends Component
{
    public string $title_en = '';
    public string $title_dv = '';
    public int $year = 2026;
    public string $description_en = '';
    public string $description_dv = '';
    public int $sort_order = 0;
    public bool $is_active = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title_en'       => ['required', 'string', 'max:255'],
            'title_dv'       => ['nullable', 'string', 'max:255'],
            'year'           => ['required', 'integer', 'min:2000', 'max:2100'],
            'description_en' => ['nullable', 'string'],
            'description_dv' => ['nullable', 'string'],
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
            'title'       => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'year'        => $this->year,
            'description' => ($this->description_en || $this->description_dv)
                              ? ['en' => $this->description_en, 'dv' => $this->description_dv]
                              : null,
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
        $this->editingId      = $cal->id;
        $this->title_en       = $cal->getTranslation('title', 'en', false) ?? '';
        $this->title_dv       = $cal->getTranslation('title', 'dv', false) ?? '';
        $this->year           = $cal->year ?? date('Y');
        $this->description_en = $cal->getTranslation('description', 'en', false) ?? '';
        $this->description_dv = $cal->getTranslation('description', 'dv', false) ?? '';
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
        $this->reset(['title_en', 'title_dv', 'description_en', 'description_dv', 'editingId']);
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

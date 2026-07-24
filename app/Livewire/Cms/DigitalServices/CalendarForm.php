<?php

namespace App\Livewire\Cms\DigitalServices;

use App\Models\DigitalServiceCalendar;
use Livewire\Component;

class CalendarForm extends Component
{
    public ?int $itemId = null;

    public string $title_en = '';
    public int $year = 2026;
    public string $description_en = '';
    public int $sort_order = 0;
    public bool $is_active = false;

    protected function rules(): array
    {
        return [
            'title_en'       => ['required', 'string', 'max:255'],
            'year'           => ['required', 'integer', 'min:2000', 'max:2100'],
            'description_en' => ['nullable', 'string'],
            'sort_order'     => ['integer', 'min:0'],
            'is_active'      => ['boolean'],
        ];
    }

    public function mount(?int $itemId = null): void
    {
        if ($itemId) {
            $cal = DigitalServiceCalendar::findOrFail($itemId);
            $this->itemId          = $cal->id;
            $this->title_en        = $cal->getTranslation('title', 'en', false) ?? '';
            $this->year            = $cal->year ?? (int) date('Y');
            $this->description_en  = $cal->getTranslation('description', 'en', false) ?? '';
            $this->sort_order      = $cal->sort_order;
            $this->is_active       = $cal->is_active;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->is_active) {
            DigitalServiceCalendar::where('id', '!=', $this->itemId ?? 0)->update(['is_active' => false]);
        }

        $data = [
            'title'       => ['en' => $this->title_en],
            'year'        => $this->year,
            'description' => $this->description_en ? ['en' => $this->description_en] : null,
            'sort_order'  => $this->sort_order,
            'is_active'   => $this->is_active,
        ];

        if ($this->itemId) {
            DigitalServiceCalendar::findOrFail($this->itemId)->update($data);
            $this->dispatch('toast', message: 'Calendar updated.');
        } else {
            DigitalServiceCalendar::create($data);
            $this->dispatch('toast', message: 'Calendar added.');
        }

        $this->redirect(route('cms.digital-services.calendars'), navigate: true);
    }

    public function render()
    {
        return view('livewire.cms.digital-services.calendar-form')
            ->layout('layouts.app', ['title' => $this->itemId ? 'Edit Calendar' : 'New Calendar']);
    }
}

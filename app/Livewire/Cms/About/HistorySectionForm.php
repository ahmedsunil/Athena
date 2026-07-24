<?php

namespace App\Livewire\Cms\About;

use App\Models\HistorySection;
use Livewire\Component;

class HistorySectionForm extends Component
{
    public ?int $sectionId = null;

    public string $title_en = '';
    public string $year_label = '';
    public string $body_en = '';
    public int $sort_order = 0;

    public function mount(?int $sectionId = null): void
    {
        if ($sectionId) {
            $item = HistorySection::findOrFail($sectionId);
            $this->sectionId  = $item->id;
            $this->title_en   = $item->getTranslation('title', 'en', false) ?? '';
            $this->year_label = $item->year_label ?? '';
            $this->body_en    = $item->getTranslation('body', 'en', false) ?? '';
            $this->sort_order = $item->sort_order;
        }
    }

    protected function rules(): array
    {
        return [
            'title_en'   => ['required', 'string', 'max:255'],
            'year_label' => ['nullable', 'string', 'max:50'],
            'body_en'    => ['required', 'string'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => ['en' => $this->title_en],
            'year_label' => $this->year_label,
            'body'       => ['en' => $this->body_en],
            'sort_order' => $this->sort_order,
        ];

        if ($this->sectionId) {
            HistorySection::findOrFail($this->sectionId)->update($data);
            $this->dispatch('toast', message: 'Section updated.');
        } else {
            HistorySection::create($data);
            $this->dispatch('toast', message: 'Section added.');
        }

        $this->redirect(route('cms.history'), navigate: true);
    }

    public function render()
    {
        $title = $this->sectionId ? 'Edit History Section' : 'New History Section';
        return view('livewire.cms.about.history-section-form')
            ->layout('layouts.app', ['title' => $title]);
    }
}

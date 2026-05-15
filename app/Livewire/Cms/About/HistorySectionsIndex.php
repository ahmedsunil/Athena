<?php

namespace App\Livewire\Cms\About;

use App\Models\HistorySection;
use Livewire\Component;

class HistorySectionsIndex extends Component
{
    public string $title_en = '';
    public string $title_dv = '';
    public string $year_label = '';
    public string $body_en = '';
    public string $body_dv = '';
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title_en'   => ['required', 'string', 'max:255'],
            'title_dv'   => ['nullable', 'string', 'max:255'],
            'year_label' => ['nullable', 'string', 'max:50'],
            'body_en'    => ['required', 'string'],
            'body_dv'    => ['nullable', 'string'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => ['en' => $this->title_en, 'dv' => $this->title_dv],
            'year_label' => $this->year_label,
            'body'       => ['en' => $this->body_en, 'dv' => $this->body_dv],
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingId) {
            HistorySection::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Section updated.');
        } else {
            HistorySection::create($data);
            $this->dispatch('toast', message: 'Section added.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $item = HistorySection::findOrFail($id);
        $this->editingId  = $item->id;
        $this->title_en   = $item->getTranslation('title', 'en', false) ?? '';
        $this->title_dv   = $item->getTranslation('title', 'dv', false) ?? '';
        $this->year_label = $item->year_label ?? '';
        $this->body_en    = $item->getTranslation('body', 'en', false) ?? '';
        $this->body_dv    = $item->getTranslation('body', 'dv', false) ?? '';
        $this->sort_order = $item->sort_order;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        HistorySection::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Section deleted.');
    }

    private function resetForm(): void
    {
        $this->reset(['title_en', 'title_dv', 'year_label', 'body_en', 'body_dv', 'sort_order', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.history-sections-index', [
            'sections' => HistorySection::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'History Sections']);
    }
}

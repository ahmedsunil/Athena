<?php

namespace App\Livewire\Cms\About;

use App\Models\HistorySection;
use Livewire\Component;

class HistorySectionsIndex extends Component
{
    public string $title = '';
    public string $year_label = '';
    public string $body = '';
    public int $sort_order = 0;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'      => ['required', 'string', 'max:255'],
            'year_label' => ['nullable', 'string', 'max:50'],
            'body'       => ['required', 'string'],
            'sort_order' => ['integer', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'      => $this->title,
            'year_label' => $this->year_label,
            'body'       => $this->body,
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
        $this->title      = $item->title;
        $this->year_label = $item->year_label ?? '';
        $this->body       = $item->body;
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
        $this->reset(['title', 'year_label', 'body', 'sort_order', 'editingId']);
    }

    public function render()
    {
        return view('livewire.cms.about.history-sections-index', [
            'sections' => HistorySection::orderBy('sort_order')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'History Sections']);
    }
}

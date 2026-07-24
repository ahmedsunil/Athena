<?php

namespace App\Livewire\Cms\About;

use App\Models\HistorySection;
use Livewire\Component;
use Livewire\WithPagination;

class HistorySectionsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        HistorySection::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Section deleted.');
    }

    public function render()
    {
        return view('livewire.cms.about.history-sections-index', [
            'sections' => HistorySection::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'History Sections']);
    }
}

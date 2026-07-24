<?php

namespace App\Livewire\Cms\Academics;

use App\Models\AcademicLevel;
use Livewire\Component;
use Livewire\WithPagination;

class AcademicLevelsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        AcademicLevel::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Level deleted.');
    }

    public function render()
    {
        return view('livewire.cms.academics.levels-index', [
            'levels' => AcademicLevel::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Academics — Levels']);
    }
}

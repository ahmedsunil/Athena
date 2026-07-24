<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifePrefect;
use Livewire\Component;
use Livewire\WithPagination;

class PrefectsIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        StudentLifePrefect::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Prefect deleted.');
    }

    public function render()
    {
        return view('livewire.cms.student-life.prefects-index', [
            'prefects' => StudentLifePrefect::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Student Life — Prefects']);
    }
}

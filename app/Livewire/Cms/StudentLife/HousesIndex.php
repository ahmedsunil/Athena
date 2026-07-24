<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeHouse;
use Livewire\Component;
use Livewire\WithPagination;

class HousesIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        StudentLifeHouse::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'House deleted.');
    }

    public function render()
    {
        return view('livewire.cms.student-life.houses-index', [
            'houses' => StudentLifeHouse::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Student Life — Houses']);
    }
}

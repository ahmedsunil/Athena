<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifeClub;
use Livewire\Component;
use Livewire\WithPagination;

class ClubsIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        StudentLifeClub::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Club deleted.');
    }

    public function render()
    {
        return view('livewire.cms.student-life.clubs-index', [
            'clubs' => StudentLifeClub::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Student Life — Clubs']);
    }
}

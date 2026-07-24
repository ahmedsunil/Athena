<?php

namespace App\Livewire\Cms\StudentLife;

use App\Models\StudentLifePerson;
use Livewire\Component;
use Livewire\WithPagination;

class PeopleIndex extends Component
{
    use WithPagination;

    public function delete(int $id): void
    {
        StudentLifePerson::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Person deleted.');
    }

    public function render()
    {
        return view('livewire.cms.student-life.people-index', [
            'people' => StudentLifePerson::with('personable')
                ->orderByDesc('is_active')
                ->orderByDesc('year')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->paginate(15),
        ])->layout('layouts.app', ['title' => 'Student Life — People History']);
    }
}

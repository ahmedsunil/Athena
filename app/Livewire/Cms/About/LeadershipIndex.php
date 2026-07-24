<?php

namespace App\Livewire\Cms\About;

use App\Models\LeadershipMember;
use Livewire\Component;
use Livewire\WithPagination;

class LeadershipIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        LeadershipMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Member deleted.');
    }

    public function render()
    {
        return view('livewire.cms.about.leadership-index', [
            'members' => LeadershipMember::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Leadership Team']);
    }
}

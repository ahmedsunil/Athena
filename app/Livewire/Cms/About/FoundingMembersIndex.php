<?php

namespace App\Livewire\Cms\About;

use App\Models\FoundingMember;
use Livewire\Component;
use Livewire\WithPagination;

class FoundingMembersIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        FoundingMember::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Founding member deleted.');
    }

    public function render()
    {
        return view('livewire.cms.about.founding-members-index', [
            'members' => FoundingMember::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Founding Teachers']);
    }
}

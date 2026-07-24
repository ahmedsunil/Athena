<?php

namespace App\Livewire\Cms\HomeQuickAccess;

use App\Models\HomeQuickAccess;
use Livewire\Component;
use Livewire\WithPagination;

class HomeQuickAccessIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        HomeQuickAccess::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Quick access item deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-quick-access.home-quick-access-index', [
            'items' => HomeQuickAccess::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Home Quick Access']);
    }
}

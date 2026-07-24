<?php

namespace App\Livewire\Cms\About;

use App\Models\Achievement;
use Livewire\Component;
use Livewire\WithPagination;

class AchievementsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        Achievement::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Achievement deleted.');
    }

    public function render()
    {
        return view('livewire.cms.about.achievements-index', [
            'achievements' => Achievement::orderByDesc('year')->orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Achievements']);
    }
}

<?php

namespace App\Livewire\Cms\HomeStats;

use App\Models\HomeStat;
use Livewire\Component;
use Livewire\WithPagination;

class HomeStatsIndex extends Component
{
    use WithPagination;
    public function delete(int $id): void
    {
        HomeStat::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Stat deleted.');
    }

    public function render()
    {
        return view('livewire.cms.home-stats.home-stats-index', [
            'stats' => HomeStat::orderBy('sort_order')->orderBy('id')->paginate(15),
        ])->layout('layouts.app', ['title' => 'Home Stats']);
    }
}

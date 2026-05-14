<?php

namespace App\Livewire\Website;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Announcements extends Component
{
    use WithPagination;

    #[Url]
    public string $filter = 'active';

    public function setFilter(string $filter): void
    {
        if (in_array($filter, ['active', 'closed'], true)) {
            $this->filter = $filter;
            $this->resetPage();
        }
    }

    public function render()
    {
        $today = now()->toDateString();

        $announcements = Announcement::query()
            ->when(
                $this->filter === 'closed',
                fn (Builder $query) => $query->where(fn (Builder $query) => $query
                    ->where('is_active', false)
                    ->orWhereDate('deadline', '<', $today)),
                fn (Builder $query) => $query
                    ->where('is_active', true)
                    ->where(fn (Builder $query) => $query
                    ->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', $today))
            )
            ->orderBy('sort_order')
            ->orderByRaw('deadline is null')
            ->orderBy('deadline')
            ->orderByDesc('created_at')
            ->paginate(5);

        return view('livewire.website.announcements', [
            'announcements' => $announcements,
        ])->layout('layouts.web');
    }
}

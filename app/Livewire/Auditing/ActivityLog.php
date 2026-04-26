<?php

namespace App\Livewire\Auditing;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $eventFilter = '';
    public string $dateFrom    = '';
    public string $dateTo      = '';

    protected $queryString = [
        'search'      => ['except' => ''],
        'eventFilter' => ['except' => ''],
        'dateFrom'    => ['except' => ''],
        'dateTo'      => ['except' => ''],
    ];

    public function updatingSearch(): void      { $this->resetPage(); }
    public function updatingEventFilter(): void { $this->resetPage(); }
    public function updatingDateFrom(): void    { $this->resetPage(); }
    public function updatingDateTo(): void      { $this->resetPage(); }

    public function render()
    {
        $logs = Activity::with(['causer', 'subject'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('description', 'like', "%{$this->search}%")
                  ->orWhereHasMorph('causer', [\App\Models\User::class], fn ($q) =>
                      $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                  );
            }))
            ->when($this->eventFilter, fn ($q) => $q->where('event', $this->eventFilter))
            ->when($this->dateFrom,    fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo,      fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(25);

        return view('livewire.auditing.activity-log', compact('logs'))
            ->layout('layouts.app', ['title' => 'Activity Log']);
    }
}

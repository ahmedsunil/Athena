<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public array $selected = [];
    public bool $selectAll = false;
    public bool $confirmingDelete = false;
    public ?int $deletingId = null;

    protected $queryString = [
        'search'       => ['except' => ''],
        'roleFilter'   => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField'    => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selected = User::query()
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                }))
                ->when($this->roleFilter, fn ($q) => $q->where('role', $this->roleFilter))
                ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', (bool) $this->statusFilter))
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteUser(): void
    {
        $user = User::findOrFail($this->deletingId);

        if ($user->id === Auth::id()) {
            return;
        }

        $user->delete();
        $this->confirmingDelete = false;
        $this->deletingId = null;
        $this->dispatch('notify', message: 'User deleted.');
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
    }

    public function bulkDelete(): void
    {
        User::whereIn('id', $this->selected)
            ->where('id', '!=', Auth::id())
            ->delete();

        $this->selected = [];
        $this->selectAll = false;
        $this->dispatch('notify', message: 'Selected users deleted.');
    }

    public function render()
    {
        $query = User::query()
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn ($q) => $q->where('role', $this->roleFilter))
            ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', (bool) $this->statusFilter))
            ->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate(15);

        return view('livewire.users.users-list', compact('users'))
            ->layout('layouts.app', ['title' => 'Users']);
    }
}

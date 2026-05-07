<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

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
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function clearSelection(): void
    {
        $this->selected = [];
        $this->selectAll = false;
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function updatingSortField(): void
    {
        $this->clearSelection();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selected = User::query()
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                }))
                ->when($this->roleFilter, fn ($q) => $q->role($this->roleFilter))
                ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', $this->statusFilter === '1'))
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        if ($this->selected === []) {
            $this->selectAll = false;
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

        activity()->causedBy(Auth::user())->performedOn($user)->log('deleted');
        $user->delete();
        $this->confirmingDelete = false;
        $this->deletingId = null;
        $this->dispatch('toast', message: 'User deleted.');
    }

    public function toggleActive(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);
        activity()->causedBy(Auth::user())->performedOn($user)->log('updated');
    }

    public function bulkDelete(): void
    {
        $users = User::whereIn('id', $this->selected)
            ->where('id', '!=', Auth::id())
            ->get();

        foreach ($users as $user) {
            activity()->causedBy(Auth::user())->performedOn($user)->log('deleted');
            $user->delete();
        }

        $this->clearSelection();
        $this->dispatch('toast', message: 'Selected users deleted.');
    }

    public function render()
    {
        $query = User::with('roles')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->roleFilter, fn ($q) => $q->role($this->roleFilter))
            ->when($this->statusFilter !== '', fn ($q) => $q->where('is_active', $this->statusFilter === '1'))
            ->orderBy($this->sortField, $this->sortDirection);

        $users = $query->paginate(15);
        $allRoles = Role::orderBy('name')->pluck('name');

        return view('livewire.users.users-list', compact('users', 'allRoles'))
            ->layout('layouts.app', ['title' => 'Users']);
    }
}

<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RolesList extends Component
{
    public bool $confirmingDelete = false;
    public ?int $deletingId = null;

    protected array $systemRoles = ['admin', 'manager', 'user'];

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteRole(): void
    {
        $role = Role::findOrFail($this->deletingId);

        if (in_array($role->name, $this->systemRoles)) {
            $this->dispatch('toast', message: 'System roles cannot be deleted.', type: 'error');
            $this->confirmingDelete = false;
            return;
        }

        $role->delete();
        $this->confirmingDelete = false;
        $this->deletingId = null;
        $this->dispatch('toast', message: 'Role deleted.');
    }

    public function render()
    {
        $roles = Role::with('permissions')->withCount('users')->orderBy('name')->get();

        $roles->each(function (Role $role) {
            $role->permission_resources = $role->permissions
                ->pluck('name')
                ->map(fn (string $permission) => Str::before($permission, '.'))
                ->unique()
                ->map(fn (string $resource) => Str::of($resource)->replace(['-', '_'], ' ')->headline()->toString())
                ->sort()
                ->values();
        });

        return view('livewire.roles.roles-list', compact('roles'))
            ->layout('layouts.app', ['title' => 'Roles']);
    }
}

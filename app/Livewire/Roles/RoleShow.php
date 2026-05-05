<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleShow extends Component
{
    #[Locked]
    public int $roleId;

    public function mount(int $roleId): void
    {
        Role::findOrFail($roleId);

        $this->roleId = $roleId;
    }

    public function render()
    {
        $role = Role::with('permissions')->withCount('users')->findOrFail($this->roleId);
        $resources = $role->permissions
            ->pluck('name')
            ->map(fn (string $permission) => Str::before($permission, '.'))
            ->unique()
            ->map(fn (string $resource) => Str::of($resource)->replace(['-', '_'], ' ')->headline()->toString())
            ->sort()
            ->values();
        $permissions = $role->permissions->sortBy('name')->groupBy(function ($permission) {
            return Str::of(Str::before($permission->name, '.'))->replace(['-', '_'], ' ')->headline()->toString();
        });

        return view('livewire.roles.role-show', compact('role', 'resources', 'permissions'))
            ->layout('layouts.app', ['title' => 'View Role']);
    }
}

<?php

namespace App\Livewire\Roles;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleForm extends Component
{
    #[Locked]
    public ?int $roleId = null;

    public string $name = '';
    public array $selectedPermissions = [];
    public string $permissionSearch = '';

    public function mount(?int $roleId = null): void
    {
        if ($roleId) {
            $role = Role::with('permissions')->findOrFail($roleId);
            $this->roleId = $role->id;
            $this->name   = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function save(): void
    {
        $this->validate([
            'name'                   => ['required', 'string', 'max:64', Rule::unique('roles', 'name')->ignore($this->roleId)],
            'selectedPermissions'    => ['array'],
            'selectedPermissions.*'  => ['string', 'exists:permissions,name'],
        ]);

        if ($this->roleId) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $message = 'Role updated.';
        } else {
            $role    = Role::create(['name' => $this->name]);
            $message = 'Role created.';
        }

        $role->syncPermissions($this->selectedPermissions);

        $this->dispatch('toast', message: $message);
        $this->redirect(route('roles.index'), navigate: true);
    }

    public function togglePermission(string $permission): void
    {
        if (in_array($permission, $this->selectedPermissions, true)) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permission]));
            return;
        }

        $this->selectedPermissions[] = $permission;
    }

    public function togglePermissionGroup(string $group, array $permissions): void
    {
        $selected = collect($this->selectedPermissions);
        $allSelected = collect($permissions)->every(fn (string $permission) => $selected->contains($permission));

        $this->selectedPermissions = $allSelected
            ? $selected->reject(fn (string $permission) => in_array($permission, $permissions, true))->values()->all()
            : $selected->merge($permissions)->unique()->values()->all();
    }

    public function render()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(function ($p) {
            return ucfirst(explode('.', $p->name)[0]);
        })->map(function ($items, $group) {
            $query = mb_strtolower(trim($this->permissionSearch));

            if ($query === '') {
                return $items;
            }

            return $items->filter(fn ($permission) =>
                str_contains(mb_strtolower($permission->name), $query)
                || str_contains(mb_strtolower((string) $group), $query)
            );
        })->filter(fn ($items) => $items->isNotEmpty());

        return view('livewire.roles.role-form', compact('permissions'))
            ->layout('layouts.app', ['title' => $this->roleId ? 'Edit Role' : 'Create Role']);
    }
}

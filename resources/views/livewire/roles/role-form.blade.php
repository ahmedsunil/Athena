<div class="mx-auto max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">{{ $this->roleId ? 'Edit Role' : 'Create Role' }}</h1>
            <p class="mt-1 admin-caption">Define a role name and assign permissions.</p>
        </div>
        <a href="{{ route('roles.index') }}" wire:navigate
           class="admin-link-label text-zinc-500 hover:text-zinc-950">
            ← Back to Roles
        </a>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm space-y-6">
        {{-- Name --}}
        <div>
            <label class="mb-1.5 block admin-label">Role name</label>
            <input type="text" wire:model="name" placeholder="e.g. editor"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Permissions grouped + searchable --}}
        <div>

            <div class="mb-3 flex items-center justify-between gap-3">
                <p class="admin-label">Permissions</p>
                <div class="flex items-center gap-2 rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1.5 w-48">
                    <svg class="h-3.5 w-3.5 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0Z"/>
                    </svg>
                    <input wire:model.live.debounce.250ms="permissionSearch"
                           type="text"
                           placeholder="Search permissions…"
                           class="w-full bg-transparent text-xs font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:outline-none">
                    @if($permissionSearch !== '')
                    <button type="button" wire:click="$set('permissionSearch', '')" class="shrink-0 text-zinc-400 hover:text-zinc-700">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                @forelse($permissions as $group => $groupPermissions)
                    @php
                        $permissionNames = $groupPermissions->pluck('name')->values()->all();
                        $allSelected = collect($permissionNames)->every(fn ($permission) => in_array($permission, $selectedPermissions, true));
                    @endphp
                    <div class="rounded-lg border border-zinc-200 p-4">
                        <div class="mb-2.5 flex items-center justify-between gap-2">
                            <p class="admin-eyebrow">{{ $group }}</p>
                            <button type="button"
                                    wire:click="togglePermissionGroup(@js($group), @js($permissionNames))"
                                    class="text-[10px] font-medium text-zinc-400 hover:text-zinc-700 transition-colors">
                                {{ $allSelected ? 'Deselect all' : 'Select all' }}
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-2 sm:grid-cols-3">
                            @foreach($groupPermissions as $permission)
                                <label class="flex cursor-pointer items-center gap-2">
                                    <input type="checkbox"
                                           value="{{ $permission->name }}"
                                           wire:click="togglePermission(@js($permission->name))"
                                           @checked(in_array($permission->name, $selectedPermissions, true))
                                           class="h-3.5 w-3.5 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                                    <span class="text-xs font-normal leading-5 text-zinc-700">{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="rounded-lg border border-zinc-200 px-4 py-6 text-center admin-muted">
                        No permissions match "{{ $permissionSearch }}".
                    </p>
                @endforelse
            </div>

            @error('selectedPermissions') <p class="mt-2 admin-form-error">{{ $message }}</p> @enderror
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3 pt-1">
            <button wire:click="save"
                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-950 px-4 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60">
                <svg wire:loading wire:target="save" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                {{ $this->roleId ? 'Update Role' : 'Create Role' }}
            </button>
            <a href="{{ route('roles.index') }}" wire:navigate
               class="admin-link-label text-zinc-500 hover:text-zinc-950">Cancel</a>
        </div>
    </div>
</div>

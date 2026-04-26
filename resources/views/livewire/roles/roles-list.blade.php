<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-base font-semibold text-zinc-950">Roles</h1>
            <p class="mt-1 text-xs text-zinc-500">Manage roles and their permissions.</p>
        </div>
        <a href="{{ route('roles.create') }}" wire:navigate
           class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-950 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-zinc-800">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Role
        </a>
    </div>

    {{-- Roles grid --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($roles as $role)
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="mb-3 flex items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-zinc-950 capitalize">{{ $role->name }}</p>
                        <p class="text-xs text-zinc-400">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                           class="rounded-md px-2.5 py-1.5 text-xs font-medium text-zinc-700 border border-zinc-200 hover:bg-zinc-50">
                            Edit
                        </a>
                        @if(!in_array($role->name, ['admin', 'manager', 'user']))
                            <button wire:click="confirmDelete({{ $role->id }})"
                                    class="rounded-md px-2.5 py-1.5 text-xs font-medium text-red-600 border border-red-200 hover:bg-red-50">
                                Delete
                            </button>
                        @endif
                    </div>
                </div>

                @if($role->permissions->count())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($role->permissions->sortBy('name') as $perm)
                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-600">
                                {{ $perm->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-zinc-400 italic">No permissions assigned.</p>
                @endif
            </div>
        @empty
            <div class="col-span-3 rounded-xl border border-zinc-200 bg-white py-16 text-center">
                <p class="text-sm text-zinc-400">No roles found.</p>
            </div>
        @endforelse
    </div>

    {{-- Delete confirmation modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/40 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-xl border border-zinc-200 bg-white p-6 shadow-xl">
                <h3 class="text-sm font-semibold text-zinc-950">Delete role?</h3>
                <p class="mt-1 text-xs text-zinc-500">This will remove the role and unassign it from all users.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="$set('confirmingDelete', false)"
                            class="rounded-lg border border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50">
                        Cancel
                    </button>
                    <button wire:click="deleteRole"
                            class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

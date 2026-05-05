<div>
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Roles</h1>
            <p class="mt-1 admin-caption">Manage roles and their permissions.</p>
        </div>
        <a href="{{ route('roles.create') }}" wire:navigate
           class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-950 px-3.5 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-800">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Create Role
        </a>
    </div>

    {{-- Desktop table --}}
    <div class="hidden overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm md:block">
        <table class="admin-table">
            <thead>
                <tr class="border-b border-zinc-100 text-left">
                    <th class="px-4 py-3 admin-link-label text-zinc-500">ID</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Role</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Users</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Resources</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="admin-table-body">
                @forelse($roles as $role)
                    <tr>
                        <td class="px-4 py-3 admin-muted whitespace-nowrap">#{{ $role->id }}</td>
                        <td class="admin-table-cell-primary capitalize">{{ $role->name }}</td>
                        <td class="px-4 py-3 admin-muted">
                            {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                        </td>
                        <td class="px-4 py-3">
                            @if($role->permission_resources->count())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($role->permission_resources as $resource)
                                        <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-600">
                                            {{ $resource }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="admin-muted italic">No resources assigned.</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                                   class="admin-link-label text-zinc-950 hover:text-zinc-800">
                                    Edit
                                </a>
                                @if(!in_array($role->name, ['admin', 'manager', 'user']))
                                    <button wire:click="confirmDelete({{ $role->id }})"
                                            class="admin-link-label text-red-500 hover:text-red-700">
                                        Delete
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center admin-body-muted">No roles found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-2 md:hidden">
        @forelse($roles as $role)
            <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                <div class="mb-3 flex items-start justify-between gap-2">
                    <div>
                        <p class="admin-section-title capitalize">{{ $role->name }}</p>
                        <p class="admin-muted">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</p>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
                           class="rounded-md px-2.5 py-1.5 admin-label border border-zinc-200 hover:bg-zinc-50">
                            Edit
                        </a>
                        @if(!in_array($role->name, ['admin', 'manager', 'user']))
                            <button wire:click="confirmDelete({{ $role->id }})"
                                    class="rounded-md px-2.5 py-1.5 admin-link-label text-red-600 border border-red-200 hover:bg-red-50">
                                Delete
                            </button>
                        @endif
                    </div>
                </div>

                @if($role->permission_resources->count())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($role->permission_resources as $resource)
                            <span class="inline-flex items-center rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-600">
                                {{ $resource }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="admin-muted italic">No resources assigned.</p>
                @endif
            </div>
        @empty
            <div class="rounded-xl border border-zinc-200 bg-white py-16 text-center shadow-sm">
                <p class="admin-body-muted">No roles found.</p>
            </div>
        @endforelse
    </div>

    {{-- Delete confirmation modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-950/40 backdrop-blur-sm">
            <div class="w-full max-w-sm rounded-xl border border-zinc-200 bg-white p-6 shadow-xl">
                <h3 class="admin-section-title">Delete role?</h3>
                <p class="mt-1 admin-caption">This will remove the role and unassign it from all users.</p>
                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="$set('confirmingDelete', false)"
                            class="rounded-lg border border-zinc-300 px-3 py-1.5 admin-label hover:bg-zinc-50">
                        Cancel
                    </button>
                    <button wire:click="deleteRole"
                            class="rounded-lg bg-red-600 px-3 py-1.5 admin-link-label text-white hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

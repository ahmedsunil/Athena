<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-semibold text-zinc-950">Users</h1>
            <p class="text-xs text-zinc-400">Manage user accounts and permissions.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="rounded-lg bg-zinc-950 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-zinc-800">
            + New user
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap items-center gap-2">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search name or email…"
               class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 sm:w-64">

        <select wire:model.live="roleFilter"
                class="rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            <option value="">All roles</option>
            @foreach($allRoles as $roleName)
                <option value="{{ $roleName }}">{{ ucfirst($roleName) }}</option>
            @endforeach
        </select>

        <div class="inline-flex h-9 overflow-hidden rounded-lg border border-zinc-200 bg-white p-0.5 shadow-sm">
            <button type="button" wire:click="$set('statusFilter', '')"
                    class="rounded-md px-3 text-xs font-medium transition-colors {{ $statusFilter === '' ? 'bg-zinc-950 text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }}">
                All
            </button>
            <button type="button" wire:click="$set('statusFilter', '1')"
                    class="rounded-md px-3 text-xs font-medium transition-colors {{ $statusFilter === '1' ? 'bg-zinc-950 text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }}">
                Active
            </button>
            <button type="button" wire:click="$set('statusFilter', '0')"
                    class="rounded-md px-3 text-xs font-medium transition-colors {{ $statusFilter === '0' ? 'bg-zinc-950 text-white' : 'text-zinc-600 hover:bg-zinc-50 hover:text-zinc-950' }}">
                Inactive
            </button>
        </div>
    </div>

    {{-- Bulk action bar --}}
    @if(count($selected))
        <div class="flex items-center gap-3 rounded-lg bg-zinc-800 px-4 py-2.5">
            <span class="text-xs font-medium text-white">{{ count($selected) }} selected</span>
            <button wire:click="bulkDelete" wire:confirm="Delete selected users? This cannot be undone."
                    class="ml-auto rounded-md bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700">
                Delete selected
            </button>
            <button wire:click="clearSelection"
                    class="text-xs text-zinc-400 hover:text-white">
                Deselect all
            </button>
        </div>
    @endif

    {{-- Desktop table --}}
    <div class="hidden overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm md:block">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-100 text-left">
                    <th class="px-4 py-3">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-zinc-300 text-zinc-950">
                    </th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('name')" class="flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-700">
                            Name
                            @if($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('email')" class="flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-700">
                            Email
                            @if($sortField === 'email') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Role</th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Status</th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('created_at')" class="flex items-center gap-1 text-xs font-medium text-zinc-500 hover:text-zinc-700">
                            Joined
                            @if($sortField === 'created_at') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="admin-table-body">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $user->id }}"
                                   class="rounded border-zinc-300 text-zinc-950">
                        </td>
                        <td class="px-4 py-3 font-medium text-zinc-950">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-zinc-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($user->roles->sortBy('name') as $role)
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-700">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                                @if($user->roles->isEmpty())
                                    <span class="text-xs text-zinc-400">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleActive({{ $user->id }})"
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium transition-colors
                                        {{ $user->is_active ? 'bg-zinc-100 text-zinc-950 hover:bg-zinc-200' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-zinc-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-xs font-medium text-zinc-950 hover:text-zinc-800">Edit</a>
                                @if($user->id !== auth()->id())
                                    <button wire:click="confirmDelete({{ $user->id }})"
                                            class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-zinc-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="border-t border-zinc-100 px-4 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-2 md:hidden">
        @forelse($users as $user)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-zinc-950">{{ $user->name }}</p>
                        <p class="text-xs text-zinc-400">{{ $user->email }}</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($user->roles->sortBy('name') as $role)
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-700">
                                    {{ ucfirst($role->name) }}
                                </span>
                            @endforeach
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->is_active ? 'bg-zinc-100 text-zinc-950' : 'bg-red-50 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <a href="{{ route('users.edit', $user) }}"
                           class="text-xs font-medium text-zinc-950 hover:text-zinc-800">Edit</a>
                        @if($user->id !== auth()->id())
                            <button wire:click="confirmDelete({{ $user->id }})"
                                    class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-zinc-200 bg-white px-4 py-12 text-center shadow-sm">
                <p class="text-sm text-zinc-400">No users found.</p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div class="pt-2">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Delete confirmation modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="text-sm font-semibold text-zinc-950">Delete user?</h3>
                <p class="mt-1 text-xs text-zinc-500">This action cannot be undone.</p>
                <div class="mt-4 flex gap-2">
                    <button wire:click="deleteUser"
                            class="flex-1 rounded-lg bg-red-600 py-2 text-xs font-semibold text-white hover:bg-red-700">
                        Delete
                    </button>
                    <button wire:click="$set('confirmingDelete', false)"
                            class="flex-1 rounded-lg border border-zinc-300 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

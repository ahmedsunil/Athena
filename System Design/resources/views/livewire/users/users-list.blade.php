<div class="space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-base font-semibold text-stone-900">Users</h1>
            <p class="text-xs text-stone-400">Manage user accounts and permissions.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="rounded-lg bg-teal-600 px-3 py-2 text-xs font-semibold text-white shadow-sm hover:bg-teal-700">
            + New user
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2">
        <input type="text" wire:model.live.debounce.300ms="search"
               placeholder="Search name or email…"
               class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500 sm:w-64">

        <select wire:model.live="roleFilter"
                class="rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <option value="">All roles</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>

        <select wire:model.live="statusFilter"
                class="rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <option value="">All statuses</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </div>

    {{-- Bulk action bar --}}
    @if(count($selected))
        <div class="flex items-center gap-3 rounded-lg bg-stone-800 px-4 py-2.5">
            <span class="text-xs font-medium text-white">{{ count($selected) }} selected</span>
            <button wire:click="bulkDelete" wire:confirm="Delete selected users? This cannot be undone."
                    class="ml-auto rounded-md bg-red-600 px-3 py-1 text-xs font-medium text-white hover:bg-red-700">
                Delete selected
            </button>
            <button wire:click="$set('selected', [])"
                    class="text-xs text-stone-400 hover:text-white">
                Deselect all
            </button>
        </div>
    @endif

    {{-- Desktop table --}}
    <div class="hidden overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm md:block">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-stone-100 text-left">
                    <th class="px-4 py-3">
                        <input type="checkbox" wire:model.live="selectAll" class="rounded border-stone-300 text-teal-600">
                    </th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('name')" class="flex items-center gap-1 text-xs font-medium text-stone-500 hover:text-stone-700">
                            Name
                            @if($sortField === 'name') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('email')" class="flex items-center gap-1 text-xs font-medium text-stone-500 hover:text-stone-700">
                            Email
                            @if($sortField === 'email') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-stone-500">Role</th>
                    <th class="px-4 py-3 text-xs font-medium text-stone-500">Status</th>
                    <th class="px-4 py-3">
                        <button wire:click="sortBy('created_at')" class="flex items-center gap-1 text-xs font-medium text-stone-500 hover:text-stone-700">
                            Joined
                            @if($sortField === 'created_at') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                        </button>
                    </th>
                    <th class="px-4 py-3 text-xs font-medium text-stone-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-stone-50">
                @forelse($users as $user)
                    <tr class="hover:bg-stone-50">
                        <td class="px-4 py-3">
                            <input type="checkbox" wire:model.live="selected" value="{{ $user->id }}"
                                   class="rounded border-stone-300 text-teal-600">
                        </td>
                        <td class="px-4 py-3 font-medium text-stone-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-stone-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-teal-50 text-teal-700' : 'bg-stone-100 text-stone-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleActive({{ $user->id }})"
                                    class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium transition-colors
                                        {{ $user->is_active ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-red-50 text-red-700 hover:bg-red-100' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-stone-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-xs font-medium text-teal-600 hover:text-teal-700">Edit</a>
                                @if($user->id !== auth()->id())
                                    <button wire:click="confirmDelete({{ $user->id }})"
                                            class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-sm text-stone-400">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="border-t border-stone-100 px-4 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Mobile cards --}}
    <div class="space-y-2 md:hidden">
        @forelse($users as $user)
            <div class="rounded-2xl border border-stone-200 bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-stone-900">{{ $user->name }}</p>
                        <p class="text-xs text-stone-400">{{ $user->email }}</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-teal-50 text-teal-700' : 'bg-stone-100 text-stone-600' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $user->is_active ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <a href="{{ route('users.edit', $user) }}"
                           class="text-xs font-medium text-teal-600 hover:text-teal-700">Edit</a>
                        @if($user->id !== auth()->id())
                            <button wire:click="confirmDelete({{ $user->id }})"
                                    class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-stone-200 bg-white px-4 py-12 text-center shadow-sm">
                <p class="text-sm text-stone-400">No users found.</p>
            </div>
        @endforelse

        @if($users->hasPages())
            <div class="pt-2">{{ $users->links() }}</div>
        @endif
    </div>

    {{-- Delete confirmation modal --}}
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-xl">
                <h3 class="text-sm font-semibold text-stone-900">Delete user?</h3>
                <p class="mt-1 text-xs text-stone-500">This action cannot be undone.</p>
                <div class="mt-4 flex gap-2">
                    <button wire:click="deleteUser"
                            class="flex-1 rounded-lg bg-red-600 py-2 text-xs font-semibold text-white hover:bg-red-700">
                        Delete
                    </button>
                    <button wire:click="$set('confirmingDelete', false)"
                            class="flex-1 rounded-lg border border-stone-300 py-2 text-xs font-medium text-stone-700 hover:bg-stone-50">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

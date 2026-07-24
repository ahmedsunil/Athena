<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Prefects</h1>
            <p class="admin-muted">Manage prefects and their roles.</p>
        </div>
        <a href="{{ route('cms.prefects.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add prefect
        </a>
    </div>

    {{-- Prefects list --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Role</th>
            <th class="admin-table-heading">Class</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($prefects as $prefect)
                <tr>
                    <td class="admin-table-cell">
                        @if($prefect->photo_path)
                            <img src="{{ $prefect->photo_url }}" alt="{{ $prefect->name }}" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-[10px] font-black {{ $prefect->role_colour }}">
                                {{ $prefect->initials }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $prefect->name }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $prefect->role_colour }}">
                            {{ $prefect->role }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $prefect->class_name ?? '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $prefect->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $prefect->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.prefects.edit', $prefect->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $prefect->id }})" wire:confirm="Delete this prefect?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No prefects yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($prefects as $prefect)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($prefect->photo_path)
                        <img src="{{ $prefect->photo_url }}" alt="{{ $prefect->name }}" class="h-9 w-9 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="h-9 w-9 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $prefect->role_colour }}">
                            {{ $prefect->initials }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $prefect->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $prefect->role }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.prefects.edit', $prefect->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $prefect->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No prefects yet.</li>
            @endforelse
        </x-slot>
    <x-slot name="pagination">
            {{ $prefects->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

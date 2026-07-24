<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Clubs</h1>
            <p class="admin-muted">Manage clubs and societies.</p>
        </div>
        <a href="{{ route('cms.clubs.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add club
        </a>
    </div>

    {{-- Clubs list --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Logo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Schedule</th>
            <th class="admin-table-heading">Patron</th>
            <th class="admin-table-heading">President</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($clubs as $club)
                <tr>
                    <td class="admin-table-cell">
                        @if($club->logo_path)
                            <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="h-8 w-8 rounded-lg object-cover">
                        @else
                            <div class="h-8 w-8 rounded-lg bg-zinc-100 flex items-center justify-center text-[10px] font-black text-zinc-500">
                                {{ $club->initials }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $club->name }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $club->meeting_schedule ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $club->patron_name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">
                        @if($club->president_name)
                            {{ $club->president_name }}
                            @if($club->president_class)
                                <span class="text-zinc-400">· {{ $club->president_class }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $club->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $club->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.clubs.edit', $club->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $club->id }})" wire:confirm="Delete this club?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No clubs yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($clubs as $club)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($club->logo_path)
                        <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="h-9 w-9 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="h-9 w-9 rounded-lg bg-zinc-100 flex-shrink-0 flex items-center justify-center text-[10px] font-black text-zinc-500">
                            {{ $club->initials }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $club->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $club->meeting_schedule ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.clubs.edit', $club->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $club->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No clubs yet.</li>
            @endforelse
        </x-slot>
    <x-slot name="pagination">
            {{ $clubs->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

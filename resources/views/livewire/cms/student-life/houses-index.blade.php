<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Houses</h1>
            <p class="admin-muted">Manage school houses.</p>
        </div>
        <a href="{{ route('cms.houses.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add house
        </a>
    </div>

    {{-- Houses list --}}
    @php
    $colourSwatches = [
        'rose'    => 'bg-[#002366]',
        'sky'     => 'bg-sky-500',
        'emerald' => 'bg-emerald-500',
        'amber'   => 'bg-amber-500',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Colour</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Motto</th>
            <th class="admin-table-heading">House Master</th>
            <th class="admin-table-heading">Captain</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($houses as $house)
                <tr>
                    <td class="admin-table-cell">
                        <span class="inline-block h-4 w-4 rounded-full {{ $colourSwatches[$house->colour] ?? 'bg-slate-400' }}"></span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $house->name }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500 italic">{{ $house->motto ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $house->house_master_name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">
                        @if($house->captain_name)
                            {{ $house->captain_name }}
                            @if($house->captain_class)
                                <span class="text-zinc-400">· {{ $house->captain_class }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $house->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $house->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.houses.edit', $house->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $house->id }})" wire:confirm="Delete this house?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No houses yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($houses as $house)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="h-8 w-8 rounded-full flex-shrink-0 {{ $colourSwatches[$house->colour] ?? 'bg-slate-400' }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $house->name }}</p>
                        <p class="truncate text-xs text-zinc-500 italic">{{ $house->motto ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.houses.edit', $house->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $house->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No houses yet.</li>
            @endforelse
        </x-slot>
    <x-slot name="pagination">
            {{ $houses->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

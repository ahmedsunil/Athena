<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">People History</h1>
            <p class="admin-muted">Yearly people history for clubs, houses, and uniform bodies.</p>
        </div>
        <a href="{{ route('cms.people.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add person
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Person</th>
            <th class="admin-table-heading">Section</th>
            <th class="admin-table-heading">Item</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($people as $person)
                <tr>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-3">
                            @if($person->avatar_url)
                                <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-[10px] font-black text-zinc-500">{{ $person->initials }}</div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-zinc-950">{{ $person->name }}</p>
                                <p class="text-xs text-zinc-500">
                                    {{ $person->designation }}
                                    @if($person->grade)
                                        · {{ $person->grade }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ str($person->personable_type)->classBasename()->headline() }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $person->personable?->name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $person->year }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $person->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $person->is_active ? 'Active' : 'History' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.people.edit', $person->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $person->id }})" wire:confirm="Delete this person?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No people history yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($people as $person)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="h-9 w-9 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-[10px] font-black text-zinc-500">{{ $person->initials }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $person->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $person->personable?->name ?? '—' }} · {{ $person->year }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.people.edit', $person->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $person->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No people history yet.</li>
            @endforelse
        </x-slot>
    <x-slot name="pagination">
            {{ $people->links() }}
        </x-slot>
    </x-admin.tables.data-table>
</div>

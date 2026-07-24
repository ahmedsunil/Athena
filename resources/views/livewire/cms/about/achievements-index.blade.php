<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Achievements</h1>
            <p class="admin-muted">School, student, and staff achievements displayed on the About page.</p>
        </div>
        <a href="{{ route('cms.achievements.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add achievement
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Person</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($achievements as $a)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $a->id }}</td>
                    <td class="admin-table-cell text-zinc-500 whitespace-nowrap">{{ $a->year }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $a->category === 'students' ? 'bg-sky-100 text-sky-700' : ($a->category === 'staff' ? 'bg-violet-100 text-violet-700' : 'bg-[#002366]/10 text-[#002366]') }}">
                            {{ ucfirst($a->category) }}
                        </span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $a->title }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $a->person_name ?? '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $a->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $a->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.achievements.edit', $a->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete this achievement?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No achievements yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($achievements as $a)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $a->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ ucfirst($a->category) }} · {{ $a->year }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.achievements.edit', $a->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $achievements->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

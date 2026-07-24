<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">History Sections</h1>
            <p class="admin-muted">Timeline sections shown on the History tab. Each section renders body text as paragraphs.</p>
        </div>
        <a href="{{ route('cms.history.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add section
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($sections as $s)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $s->id }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs whitespace-nowrap">{{ $s->year_label }}</td>
                    <td class="admin-table-cell-primary">{{ $s->title }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $s->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.history.edit', $s->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete this section?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-table-cell text-center text-zinc-400">No history sections yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($sections as $s)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $s->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->year_label }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.history.edit', $s->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $sections->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

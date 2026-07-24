<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Home Stats</h1>
            <p class="admin-muted">Key statistics shown on the home page.</p>
        </div>
        <a href="{{ route('cms.home.stats.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add stat
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Value</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($stats as $stat)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $stat->id }}</td>
                    <td class="admin-table-cell-primary">{{ $stat->title }}</td>
                    <td class="admin-table-cell font-medium text-zinc-700">{{ $stat->value }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $stat->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $stat->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $stat->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.home.stats.edit', $stat->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $stat->id }})" wire:confirm="Delete this stat?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No stats yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($stats as $stat)
                <li class="flex items-center justify-between gap-3 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-zinc-950">{{ $stat->title }}</p>
                        <p class="text-sm font-bold text-zinc-700">{{ $stat->value }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.home.stats.edit', $stat->id) }}" wire:navigate class="text-xs text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $stat->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $stats->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

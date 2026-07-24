<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Apps</h1>
            <p class="admin-muted">Manage internal and external web applications.</p>
        </div>
        <a href="{{ route('cms.apps.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add app
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Icon</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">URL</th>
            <th class="admin-table-heading">Action</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($apps as $a)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $a->id }}</td>
                    <td class="admin-table-cell text-lg">{!! svg_icon($a->icon_key, 'h-5 w-5') !!}</td>
                    <td class="admin-table-cell-primary">{{ $a->title }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs max-w-[200px] truncate">{{ $a->url }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $a->action_label }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $a->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $a->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.apps.edit', $a->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete this app?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No apps yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($apps as $a)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="text-lg shrink-0">{!! svg_icon($a->icon_key, 'h-5 w-5') !!}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $a->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $a->url }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.apps.edit', $a->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $a->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $apps->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

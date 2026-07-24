<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Founding Teachers</h1>
            <p class="admin-muted">Pioneer educators honoured on the History tab.</p>
        </div>
        <a href="{{ route('cms.about.founding-members.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add founding member
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Subject</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($members as $m)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $m->id }}</td>
                    <td class="admin-table-cell">
                        @if($m->photo_path)
                            <img src="{{ $m->photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $m->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $m->subject }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $m->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.about.founding-members.edit', $m->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete this member?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No founding members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($members as $m)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($m->photo_path)
                        <img src="{{ $m->photo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $m->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $m->subject }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.about.founding-members.edit', $m->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $members->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

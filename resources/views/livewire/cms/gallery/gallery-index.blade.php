<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title text-lg">Gallery</h1>
            <p class="admin-muted">Manage photo album listings for the public gallery page.</p>
        </div>
        <a href="{{ route('cms.gallery.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add album
        </a>
    </div>

    @php
    $catColors = [
        'Events'     => 'bg-[#002366]/10 text-[#002366]',
        'Sports'     => 'bg-emerald-100 text-emerald-700',
        'Graduation' => 'bg-violet-100 text-violet-700',
        'Cultural'   => 'bg-amber-100 text-amber-700',
        'Academic'   => 'bg-sky-100 text-sky-700',
        'Trips'      => 'bg-orange-100 text-orange-700',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Cover</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Date</th>
            <th class="admin-table-heading">Photos</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($albums as $album)
                <tr>
                    <td class="admin-table-cell">
                        @if($album->cover_image_path)
                            <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" class="h-10 w-16 rounded-lg object-cover">
                        @else
                            <div class="h-10 w-16 rounded-lg bg-zinc-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $album->title }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $catColors[$album->category] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $album->category }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $album->date->format('d M Y') }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $album->photo_count }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $album->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $album->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.gallery.edit', $album->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $album->id }})" wire:confirm="Delete this album?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No albums yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($albums as $album)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($album->cover_image_path)
                        <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" class="h-10 w-14 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="h-10 w-14 rounded-lg bg-zinc-100 flex-shrink-0 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $album->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $album->category }} · {{ $album->date->format('d M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.gallery.edit', $album->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $album->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No albums yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $albums->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

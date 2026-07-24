<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Home Slides</h1>
            <p class="admin-muted">Manage the hero slideshow on the home page.</p>
        </div>
        <a href="{{ route('cms.home.slides.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add slide
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Image</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($slides as $slide)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $slide->id }}</td>
                    <td class="admin-table-cell">
                        @if($slide->image_path)
                            <img src="{{ $slide->image_url }}" alt="" class="h-10 w-16 rounded object-cover">
                        @else
                            <span class="text-xs text-zinc-400">—</span>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $slide->title }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $slide->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $slide->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $slide->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.home.slides.edit', $slide->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $slide->id }})" wire:confirm="Delete this slide?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No slides yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($slides as $slide)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="" class="h-10 w-14 shrink-0 rounded object-cover">
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $slide->title }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.home.slides.edit', $slide->id) }}" wire:navigate class="text-xs text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $slide->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $slides->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

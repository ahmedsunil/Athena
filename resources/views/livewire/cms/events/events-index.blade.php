<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Events</h1>
            <p class="admin-muted">Manage school events shown on the public events page and home page.</p>
        </div>
        <a href="{{ route('cms.events.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add event
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Cover</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Date</th>
            <th class="admin-table-heading">Featured</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($events as $event)
                <tr>
                    <td class="admin-table-cell">
                        @if($event->cover_image_path)
                            <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="h-10 w-16 rounded-md object-cover">
                        @else
                            <div class="h-10 w-16 rounded-md bg-zinc-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $event->title }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-600') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-500 text-xs whitespace-nowrap">{{ $event->formatted_date_range }}</td>
                    <td class="admin-table-cell">
                        @if($event->is_featured)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-[#002366]/5 text-[#002366]">
                                #{{ $event->featured_sort_order + 1 }}
                            </span>
                        @else
                            <span class="text-zinc-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $event->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.events.edit', $event->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $event->id }})" wire:confirm="Delete this event?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No events yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($events as $event)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($event->cover_image_path)
                        <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="h-10 w-14 flex-shrink-0 rounded-md object-cover">
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $event->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ ucfirst($event->status) }} · {{ $event->formatted_date_range }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.events.edit', $event->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $event->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No events yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $events->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

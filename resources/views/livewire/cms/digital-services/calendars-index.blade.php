<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Calendars</h1>
            <p class="admin-muted">Manage academic calendar years.</p>
        </div>
        <a href="{{ route('cms.digital-services.calendars.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add calendar
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Entries</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($calendars as $cal)
                <tr>
                    <td class="admin-table-cell-primary">{{ $cal->title }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->year ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->entries_count }}</td>
                    <td class="admin-table-cell">
                        @if($cal->is_active)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-green-50 text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500">Inactive</span>
                        @endif
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.digital-services.calendar', ['calendarId' => $cal->id]) }}"
                           class="mr-2 text-xs font-medium text-sky-600 hover:text-sky-800">Entries</a>
                        <a href="{{ route('cms.digital-services.calendars.edit', $cal->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $cal->id }})" wire:confirm="Delete this calendar and all its entries?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-table-cell text-center text-zinc-400">No calendars yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($calendars as $cal)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $cal->title }}</p>
                        <p class="text-xs text-zinc-500">{{ $cal->entries_count }} entries · {{ $cal->year ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.digital-services.calendar', ['calendarId' => $cal->id]) }}" class="text-xs text-sky-600">Entries</a>
                        <a href="{{ route('cms.digital-services.calendars.edit', $cal->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $cal->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No calendars yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $calendars->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

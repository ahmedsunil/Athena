<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Announcements</h1>
            <p class="admin-muted">Publish public notices such as job openings, bids, competitions, and circulars.</p>
        </div>
        <a href="{{ route('cms.announcements.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add announcement
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Created</th>
            <th class="admin-table-heading">Deadline</th>
            <th class="admin-table-heading">Files</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($announcements as $announcement)
                <tr>
                    <td class="admin-table-cell-primary">
                        {{ $announcement->title }}
                        @if($announcement->description)
                            <span class="block max-w-md truncate text-xs font-normal text-zinc-400">{{ Str::limit($announcement->description, 70) }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $announcement->category }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $announcement->created_at->format('j M Y') }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $announcement->formatted_deadline ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ count($announcement->attachments ?? []) }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $announcement->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.announcements.edit', $announcement->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button type="button" wire:click="delete({{ $announcement->id }})" wire:confirm="Delete this announcement?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No announcements yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($announcements as $announcement)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#002366]/5 text-[#002366]">
                        <x-icon :key="$announcement->icon_key" class="h-4 w-4" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $announcement->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $announcement->category }} · {{ $announcement->created_at->format('j M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.announcements.edit', $announcement->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button type="button" wire:click="delete({{ $announcement->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No announcements yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $announcements->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

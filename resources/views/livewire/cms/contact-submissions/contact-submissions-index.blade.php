<div class="space-y-4">

    <div class="flex items-start justify-between">
        <div>
            <h1 class="admin-page-title">Contact Submissions</h1>
            <p class="admin-muted">Messages submitted via the contact form.</p>
        </div>
        @if($unreadCount > 0)
            <span class="inline-flex items-center rounded-full bg-zinc-950 px-2.5 py-1 text-xs font-semibold text-white">
                {{ $unreadCount }} unread
            </span>
        @endif
    </div>

    @if($viewing)
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="admin-section-title">Message from {{ $viewing->name }}</h3>
                <button wire:click="closeView"
                        class="text-xs font-medium text-zinc-500 hover:text-zinc-950">Close</button>
            </div>
            <dl class="space-y-3">
                <div class="grid gap-1 sm:grid-cols-4">
                    <dt class="admin-label text-zinc-400">Name</dt>
                    <dd class="text-sm text-zinc-950 sm:col-span-3">{{ $viewing->name }}</dd>
                </div>
                <div class="grid gap-1 sm:grid-cols-4">
                    <dt class="admin-label text-zinc-400">Email</dt>
                    <dd class="text-sm text-zinc-950 sm:col-span-3">
                        <a href="mailto:{{ $viewing->email }}" class="text-blue-600 hover:underline">{{ $viewing->email }}</a>
                    </dd>
                </div>
                @if($viewing->phone)
                    <div class="grid gap-1 sm:grid-cols-4">
                        <dt class="admin-label text-zinc-400">Phone</dt>
                        <dd class="text-sm text-zinc-950 sm:col-span-3">{{ $viewing->phone }}</dd>
                    </div>
                @endif
                <div class="grid gap-1 sm:grid-cols-4">
                    <dt class="admin-label text-zinc-400">Received</dt>
                    <dd class="text-sm text-zinc-500 sm:col-span-3">{{ $viewing->created_at->diffForHumans() }}</dd>
                </div>
                <div class="grid gap-1 sm:grid-cols-4">
                    <dt class="admin-label text-zinc-400">Message</dt>
                    <dd class="text-sm text-zinc-950 sm:col-span-3 whitespace-pre-wrap">{{ $viewing->message }}</dd>
                </div>
            </dl>
            <div class="mt-4 border-t border-zinc-100 pt-4">
                <button wire:click="delete({{ $viewing->id }})" wire:confirm="Delete this submission permanently?"
                        class="text-xs font-medium text-red-500 hover:text-red-700">
                    Delete submission
                </button>
            </div>
        </div>
    @endif

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Email</th>
            <th class="admin-table-heading">Phone</th>
            <th class="admin-table-heading">Received</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($submissions as $s)
                <tr class="{{ !$s->is_read ? 'bg-zinc-50/60' : '' }}">
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $s->id }}</td>
                    <td class="admin-table-cell-primary {{ !$s->is_read ? 'font-semibold' : '' }}">{{ $s->name }}</td>
                    <td class="admin-table-cell text-zinc-600">{{ $s->email }}</td>
                    <td class="admin-table-cell text-zinc-500">{{ $s->phone ?? '—' }}</td>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap text-xs">{{ $s->created_at->diffForHumans() }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ !$s->is_read ? 'bg-blue-50 text-blue-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $s->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="view({{ $s->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">View</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete this submission?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No submissions yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($submissions as $s)
                <li class="flex items-center justify-between gap-3 px-4 py-3 {{ !$s->is_read ? 'bg-zinc-50/60' : '' }}">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-zinc-950 {{ !$s->is_read ? 'font-semibold' : '' }}">{{ $s->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->email }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <span class="text-xs text-zinc-400">{{ $s->created_at->diffForHumans() }}</span>
                        <button wire:click="view({{ $s->id }})" class="text-xs text-zinc-500 hover:text-zinc-950">View</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $submissions->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

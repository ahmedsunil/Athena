<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Uniform Bodies</h1>
            <p class="admin-muted">Manage uniformed groups and bodies.</p>
        </div>
        <a href="{{ route('cms.uniform-bodies.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add uniform body
        </a>
    </div>

    {{-- Uniform bodies list --}}
    @php
    $colourPills = [
        'rose'    => 'bg-[#002366]/10 text-[#002366]',
        'sky'     => 'bg-sky-100 text-sky-700',
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'amber'   => 'bg-amber-100 text-amber-700',
        'violet'  => 'bg-violet-100 text-violet-700',
        'teal'    => 'bg-teal-100 text-teal-700',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Type</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Schedule</th>
            <th class="admin-table-heading">Patron</th>
            <th class="admin-table-heading">Leader</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($bodies as $body)
                <tr>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold {{ $colourPills[$body->colour] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $body->group_type }}
                        </span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $body->name }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $body->meeting_schedule ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $body->patron_name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">
                        @if($body->leader_name)
                            {{ $body->leader_name }}
                            @if($body->leader_class)
                                <span class="text-zinc-400">· {{ $body->leader_class }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $body->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $body->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.uniform-bodies.edit', $body->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $body->id }})" wire:confirm="Delete this uniform body?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No uniform bodies yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($bodies as $body)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold flex-shrink-0 {{ $colourPills[$body->colour] ?? 'bg-slate-100 text-slate-600' }}">
                        {{ $body->group_type }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $body->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $body->meeting_schedule ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.uniform-bodies.edit', $body->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $body->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No uniform bodies yet.</li>
            @endforelse
        </x-slot>
    <x-slot name="pagination">
            {{ $bodies->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

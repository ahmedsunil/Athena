<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Resources</h1>
            <p class="admin-muted">Manage digital service links, tools, and resource cards.</p>
        </div>
        <a href="{{ route('cms.digital-services.resources.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add resource
        </a>
    </div>

    @php
    $colorDots = [
        'sky'     => 'bg-sky-500',
        'rose'    => 'bg-[#002366]',
        'emerald' => 'bg-emerald-500',
        'amber'   => 'bg-amber-500',
        'violet'  => 'bg-violet-500',
        'slate'   => 'bg-slate-500',
    ];
    $audColors = [
        'Parents'  => 'bg-violet-100 text-violet-700',
        'Students' => 'bg-amber-100 text-amber-700',
        'Staff'    => 'bg-slate-100 text-slate-600',
        'All'      => 'bg-zinc-100 text-zinc-600',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Icon</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Audience</th>
            <th class="admin-table-heading">URL</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($resources as $resource)
                <tr>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full {{ $colorDots[$resource->icon_color] ?? 'bg-slate-400' }}"></span>
                            <span class="text-xs text-zinc-500">{{ $resource->icon }}</span>
                        </div>
                    </td>
                    <td class="admin-table-cell-primary">
                        {{ $resource->title }}
                        @if($resource->description)
                            <span class="block text-xs text-zinc-400 font-normal truncate max-w-xs">{{ Str::limit($resource->description, 60) }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $audColors[$resource->audience] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $resource->audience }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-400 max-w-xs truncate">{{ $resource->url }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $resource->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $resource->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.digital-services.resources.edit', $resource->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $resource->id }})" wire:confirm="Delete this resource?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No resources yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($resources as $resource)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="h-3 w-3 flex-shrink-0 rounded-full {{ $colorDots[$resource->icon_color] ?? 'bg-slate-400' }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $resource->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $resource->audience }} · {{ $resource->icon }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.digital-services.resources.edit', $resource->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $resource->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No resources yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $resources->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

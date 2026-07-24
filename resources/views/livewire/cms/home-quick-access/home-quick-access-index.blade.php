<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Home Quick Access</h1>
            <p class="admin-muted">Quick access cards shown on the home page.</p>
        </div>
        <a href="{{ route('cms.home.quick-access.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add item
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Icon</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Link</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($items as $item)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $item->id }}</td>
                    <td class="admin-table-cell">
                        @php
                            $lucide = ltrim(strtolower(preg_replace(['/([A-Z])/', '/(\d+)/'], ['-$1', '-$1'], lcfirst($item->icon_key))), '-');
                        @endphp
                        <i data-lucide="{{ $lucide }}" class="h-4 w-4 text-zinc-600"></i>
                    </td>
                    <td class="admin-table-cell-primary">{{ $item->title }}</td>
                    <td class="admin-table-cell">
                        @php $isExternal = preg_match('/^https?:\/\//i', $item->link_key) === 1; @endphp
                        <span class="mr-2 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $isExternal ? 'bg-blue-50 text-blue-700' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $isExternal ? 'External' : 'Page' }}
                        </span>
                        <code class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700">{{ $item->link_key }}</code>
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $item->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $item->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.home.quick-access.edit', $item->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Delete this item?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No items yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($items as $item)
                <li class="flex items-center gap-3 px-4 py-3">
                    @php
                        $lucide = ltrim(strtolower(preg_replace(['/([A-Z])/', '/(\d+)/'], ['-$1', '-$1'], lcfirst($item->icon_key))), '-');
                    @endphp
                    <i data-lucide="{{ $lucide }}" class="h-4 w-4 shrink-0 text-zinc-500"></i>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-zinc-950">{{ $item->title }}</p>
                        @php $isExternal = preg_match('/^https?:\/\//i', $item->link_key) === 1; @endphp
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $isExternal ? 'bg-blue-50 text-blue-700' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $isExternal ? 'External' : 'Page' }}
                        </span>
                        <code class="font-mono text-xs text-zinc-500">{{ $item->link_key }}</code>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.home.quick-access.edit', $item->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $items->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>

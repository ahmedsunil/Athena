<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Footer Links</h1>
            <p class="admin-muted">Manage navigation links shown in the website footer.</p>
        </div>
        <a href="{{ route('cms.footer-links.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add link
        </a>
    </div>

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">ID</th>
            <th class="admin-table-heading">Label</th>
            <th class="admin-table-heading">Link</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($links as $link)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $link->id }}</td>
                    <td class="admin-table-cell-primary">{{ $link->label }}</td>
                    <td class="admin-table-cell">
                        @php $isExternal = preg_match('/^https?:\/\//i', $link->link_key) === 1; @endphp
                        <span class="mr-2 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $isExternal ? 'bg-blue-50 text-blue-700' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $isExternal ? 'External' : 'Page' }}
                        </span>
                        <code class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700">{{ $link->link_key }}</code>
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $link->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $link->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $link->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.footer-links.edit', $link->id) }}" wire:navigate
                           class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $link->id }})" wire:confirm="Delete this footer link?"
                                class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No footer links yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($links as $link)
                <li class="flex items-center justify-between gap-3 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-zinc-950">{{ $link->label }}</p>
                        @php $isExternal = preg_match('/^https?:\/\//i', $link->link_key) === 1; @endphp
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-medium {{ $isExternal ? 'bg-blue-50 text-blue-700' : 'bg-zinc-100 text-zinc-600' }}">
                            {{ $isExternal ? 'External' : 'Page' }}
                        </span>
                        <code class="font-mono text-xs text-zinc-500">{{ $link->link_key }}</code>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.footer-links.edit', $link->id) }}" wire:navigate class="text-xs text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $link->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:text-red-700">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
        <x-slot name="pagination">
            {{ $links->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

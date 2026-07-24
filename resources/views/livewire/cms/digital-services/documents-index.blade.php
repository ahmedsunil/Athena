<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Documents</h1>
            <p class="admin-muted">Manage digital service documents, forms, policies, and handbooks.</p>
        </div>
        <a href="{{ route('cms.digital-services.documents.create') }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Add document
        </a>
    </div>

    @php
    $ftColors = [
        'PDF'  => 'bg-[#002366]/10 text-[#002366]',
        'DOCX' => 'bg-sky-100 text-sky-700',
        'XLS'  => 'bg-emerald-100 text-emerald-700',
        'XLSX' => 'bg-emerald-100 text-emerald-700',
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
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Type</th>
            <th class="admin-table-heading">Audience</th>
            <th class="admin-table-heading">Published</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($documents as $doc)
                <tr>
                    <td class="admin-table-cell-primary">
                        {{ $doc->title }}
                        @if($doc->file_size)
                            <span class="block text-xs text-zinc-400 font-normal">{{ $doc->file_size }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $doc->category }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[10px] font-bold uppercase {{ $ftColors[$doc->file_type] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $doc->file_type }}
                        </span>
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $audColors[$doc->audience] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $doc->audience }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $doc->published_at->format('d M Y') }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $doc->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $doc->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.digital-services.documents.edit', $doc->id) }}" wire:navigate class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</a>
                        <button wire:click="delete({{ $doc->id }})" wire:confirm="Delete this document?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No documents yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($documents as $doc)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-zinc-100">
                        <span class="text-[10px] font-bold text-zinc-500">{{ $doc->file_type }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $doc->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $doc->category }} · {{ $doc->audience }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.digital-services.documents.edit', $doc->id) }}" wire:navigate class="text-xs text-zinc-500">Edit</a>
                        <button wire:click="delete({{ $doc->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No documents yet.</li>
            @endforelse
        </x-slot>
        <x-slot name="pagination">
            {{ $documents->links() }}
        </x-slot>
    </x-admin.tables.data-table>

</div>

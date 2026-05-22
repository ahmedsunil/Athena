<div class="space-y-4">

    @include('layouts.partials.cms-home-tabs')

    <div>
        <h1 class="admin-page-title">Home Quick Access</h1>
        <p class="admin-muted">Quick access cards shown on the home page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit item' : 'Add item' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Icon</label>
                    <select wire:model="icon_key"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="">Select icon…</option>
                        @foreach($iconKeys as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('icon_key') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Title (English)</label>
                    <input type="text" wire:model="title_en"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Title (ދިވެހި)</label>
                    <input type="text" wire:model="title_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Link type</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="setLinkType('internal')"
                                class="rounded-[5px] admin-link-label transition-colors {{ $link_type === 'internal' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            From dropdown
                        </button>
                        <button type="button" wire:click="setLinkType('custom')"
                                class="rounded-[5px] admin-link-label transition-colors {{ $link_type === 'custom' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">
                            Custom link
                        </button>
                    </div>
                    @error('link_type') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror

                    @if($link_type === 'internal')
                        <label class="mt-2 mb-1.5 block admin-label">Page</label>
                        <select wire:model="link_key"
                                class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <option value="">Select a link...</option>
                            @foreach($linkKeys as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    @else
                        <label class="mt-2 mb-1.5 block admin-label">External URL</label>
                        <input type="url" wire:model="custom_url" placeholder="https://example.com"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @endif
                    @error('link_key') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    @error('custom_url') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add item' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="cancel"
                            class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
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
                        <button wire:click="edit({{ $item->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
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
                        <button wire:click="edit({{ $item->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $item->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>

<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">History Sections</h1>
        <p class="admin-muted">Timeline sections shown on the History tab. Each section renders body text as paragraphs.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit section' : 'Add section' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title</label>
                    <input type="text" wire:model="title"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year label <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="year_label" placeholder="e.g. 1993 – 1995"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year_label') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Body</label>
                <p class="mb-1.5 text-xs text-zinc-400">Separate paragraphs with a blank line.</p>
                <textarea wire:model="body" rows="6"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('body') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="sort_order" min="0"
                       class="h-9 w-32 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add section' }}
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
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($sections as $s)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $s->id }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs whitespace-nowrap">{{ $s->year_label }}</td>
                    <td class="admin-table-cell-primary">{{ $s->title }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $s->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $s->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete this section?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-table-cell text-center text-zinc-400">No history sections yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($sections as $s)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $s->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $s->year_label }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $s->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $s->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>

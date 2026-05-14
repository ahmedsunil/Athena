<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit entry' : 'Add entry' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title" placeholder="e.g. Second Term Begins"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Date + End date + Type --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Date <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="date"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('date') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">End date <span class="text-zinc-400">(optional)</span></label>
                    <input type="date" wire:model="end_date"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('end_date') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Type <span class="text-red-500">*</span></label>
                    <select wire:model="type"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($types as $t)
                            <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                    @error('type') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Brief description of the calendar entry..."></textarea>
                @error('description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort order + Active --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('sort_order') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Visibility</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update entry' : 'Add entry' }}
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

    {{-- Entries list --}}
    @php
    $typeColors = [
        'term'    => 'bg-rose-100 text-rose-700',
        'holiday' => 'bg-emerald-100 text-emerald-700',
        'exam'    => 'bg-amber-100 text-amber-700',
        'event'   => 'bg-sky-100 text-sky-700',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Date</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Type</th>
            <th class="admin-table-heading">End date</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($entries as $entry)
                <tr>
                    <td class="admin-table-cell text-xs text-zinc-500 whitespace-nowrap">{{ $entry->date->format('d M Y') }}</td>
                    <td class="admin-table-cell-primary">
                        {{ $entry->title }}
                        @if($entry->description)
                            <span class="block text-xs text-zinc-400 font-normal truncate max-w-xs">{{ Str::limit($entry->description, 60) }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $typeColors[$entry->type] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $entry->type }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500 whitespace-nowrap">
                        {{ $entry->end_date?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $entry->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $entry->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $entry->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $entry->id }})" wire:confirm="Delete this entry?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No calendar entries yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($entries as $entry)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="flex-shrink-0 text-center w-10">
                        <p class="text-base font-black text-rose-600 leading-none">{{ $entry->date->format('d') }}</p>
                        <p class="text-[10px] text-zinc-400 uppercase font-semibold">{{ $entry->date->format('M') }}</p>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $entry->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ ucfirst($entry->type) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $entry->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $entry->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No entries yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

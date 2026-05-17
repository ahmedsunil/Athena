<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit calendar' : 'Add calendar' }}</h3>
            <a href="{{ route('cms.digital-services.calendar') }}"
               class="text-xs font-medium text-zinc-500 hover:text-zinc-950">← Manage entries</a>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Title + Year --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title_en" placeholder="e.g. Academic Calendar 2026"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Title (ދިވެހި)</label>
                    <input type="text" wire:model="title_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="year" min="2000" max="2100"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Description (ދިވެހި)</label>
                <textarea wire:model="description_dv" rows="2" dir="rtl"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort order + Active --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Active calendar</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Yes</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">No</button>
                    </div>
                    <p class="mt-1 text-xs text-zinc-400">Only one calendar can be active at a time.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update calendar' : 'Add calendar' }}
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
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Entries</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($calendars as $cal)
                <tr>
                    <td class="admin-table-cell-primary">{{ $cal->title }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->year ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->entries_count }}</td>
                    <td class="admin-table-cell">
                        @if($cal->is_active)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-green-50 text-green-700">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-zinc-100 text-zinc-500">Inactive</span>
                        @endif
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <a href="{{ route('cms.digital-services.calendar', ['calendarId' => $cal->id]) }}"
                           class="mr-2 text-xs font-medium text-sky-600 hover:text-sky-800">Entries</a>
                        <button wire:click="edit({{ $cal->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $cal->id }})" wire:confirm="Delete this calendar and all its entries?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="admin-table-cell text-center text-zinc-400">No calendars yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($calendars as $cal)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $cal->title }}</p>
                        <p class="text-xs text-zinc-500">{{ $cal->entries_count }} entries · {{ $cal->year ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('cms.digital-services.calendar', ['calendarId' => $cal->id]) }}" class="text-xs text-sky-600">Entries</a>
                        <button wire:click="edit({{ $cal->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $cal->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No calendars yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

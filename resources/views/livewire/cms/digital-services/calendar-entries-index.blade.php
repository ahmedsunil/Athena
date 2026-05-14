<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    @if(! $currentCalendar)
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h3 class="admin-section-title">Calendars</h3>
                <p class="admin-muted">Create a calendar first, then open it to manage its entries.</p>
            </div>
            <button type="button" wire:click="newCalendar"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                New Calendar
            </button>
        </div>

        @if($showCalendarForm)
            @include('livewire.cms.digital-services.partials.calendar-form')
        @endif

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
                        <td class="admin-table-cell-primary">
                            <button type="button" wire:click="selectCalendar({{ $cal->id }})" class="text-left hover:underline">
                                {{ $cal->title }}
                            </button>
                            @if($cal->description)
                                <span class="block max-w-md truncate text-xs font-normal text-zinc-400">{{ $cal->description }}</span>
                            @endif
                        </td>
                        <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->year ?? '—' }}</td>
                        <td class="admin-table-cell text-xs text-zinc-500">{{ $cal->entries_count }}</td>
                        <td class="admin-table-cell">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $cal->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                                {{ $cal->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="admin-table-cell whitespace-nowrap text-right">
                            <button type="button" wire:click="selectCalendar({{ $cal->id }})" class="mr-2 text-xs font-medium text-sky-600 hover:text-sky-800">Open</button>
                            <button type="button" wire:click="editCalendar({{ $cal->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                            <button type="button" wire:click="deleteCalendar({{ $cal->id }})" wire:confirm="Delete this calendar and all its entries?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="admin-table-cell text-center text-zinc-400">No calendars yet. Create one to add entries.</td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="mobile">
                @forelse($calendars as $cal)
                    <li class="flex items-center gap-3 px-4 py-3">
                        <div class="min-w-0 flex-1">
                            <button type="button" wire:click="selectCalendar({{ $cal->id }})" class="block truncate text-sm font-medium text-zinc-950">
                                {{ $cal->title }}
                            </button>
                            <p class="text-xs text-zinc-500">{{ $cal->entries_count }} entries · {{ $cal->year ?? '—' }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="selectCalendar({{ $cal->id }})" class="text-xs text-sky-600">Open</button>
                            <button type="button" wire:click="editCalendar({{ $cal->id }})" class="text-xs text-zinc-500">Edit</button>
                            <button type="button" wire:click="deleteCalendar({{ $cal->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-sm text-zinc-400">No calendars yet.</li>
                @endforelse
            </x-slot>
        </x-admin.tables.data-table>
    @else
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <button type="button" wire:click="backToCalendars" class="mb-1 text-xs font-medium text-zinc-500 hover:text-zinc-950">← Calendars</button>
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="admin-section-title">{{ $currentCalendar->title }}</h3>
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $currentCalendar->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                        {{ $currentCalendar->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($currentCalendar->year)
                        <span class="text-xs text-zinc-400">{{ $currentCalendar->year }}</span>
                    @endif
                </div>
                @if($currentCalendar->description)
                    <p class="admin-muted">{{ $currentCalendar->description }}</p>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <button type="button" wire:click="$set('showSyncModal', true)"
                        class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Sync Events
                </button>
                <button type="button" wire:click="editCalendar({{ $currentCalendar->id }})"
                        class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Edit Calendar
                </button>
                <button type="button" wire:click="newEntry"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    New Entry
                </button>
            </div>
        </div>

        @if($showCalendarForm)
            @include('livewire.cms.digital-services.partials.calendar-form')
        @endif

        @if($showEntryForm)
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="mb-4">
                    <h3 class="admin-section-title">{{ $editingId ? 'Edit entry' : 'New entry' }}</h3>
                </div>
                <form wire:submit="save" class="space-y-4">
                    <div>
                        <label class="mb-1.5 block admin-label">Title <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" placeholder="e.g. First Term Begins"
                               class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>

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

                    <div>
                        <label class="mb-1.5 block admin-label">Description <span class="text-zinc-400">(optional)</span></label>
                        <textarea wire:model="description" rows="3"
                                  class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                        @error('description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" wire:model="is_tentative"
                                   class="w-4 h-4 rounded border-zinc-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-sm font-medium text-zinc-700">Tentative <span class="text-zinc-400 font-normal">— subject to change</span></span>
                        </label>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block admin-label">Sort order</label>
                            <input type="number" wire:model="sort_order" min="0"
                                   class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
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

                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                            {{ $editingId ? 'Update entry' : 'Add entry' }}
                        </button>
                        <button type="button" wire:click="cancel"
                                class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @endif

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
                        <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $entry->date->format('d M Y') }}</td>
                        <td class="admin-table-cell-primary">
                            <span class="flex items-center gap-1.5">
                                {{ $entry->title }}
                                @if($entry->is_tentative)
                                    <span class="inline-block rounded-full bg-amber-100 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wide text-amber-700">Tentative</span>
                                @endif
                            </span>
                            @if($entry->description)
                                <span class="block max-w-xs truncate text-xs font-normal text-zinc-400">{{ Str::limit($entry->description, 60) }}</span>
                            @endif
                        </td>
                        <td class="admin-table-cell">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $typeColors[$entry->type] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $entry->type }}
                            </span>
                        </td>
                        <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $entry->end_date?->format('d M Y') ?? '—' }}</td>
                        <td class="admin-table-cell">
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $entry->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                                {{ $entry->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="admin-table-cell whitespace-nowrap text-right">
                            <button type="button" wire:click="edit({{ $entry->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                            <button type="button" wire:click="delete({{ $entry->id }})" wire:confirm="Delete this entry?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="admin-table-cell text-center text-zinc-400">No entries yet. Use New Entry to add one.</td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="mobile">
                @forelse($entries as $entry)
                    <li class="flex items-center gap-3 px-4 py-3">
                        <div class="w-10 flex-shrink-0 text-center">
                            <p class="text-base font-black leading-none text-rose-600">{{ $entry->date->format('d') }}</p>
                            <p class="text-[10px] font-semibold uppercase text-zinc-400">{{ $entry->date->format('M') }}</p>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium text-zinc-950">{{ $entry->title }}</p>
                            <p class="truncate text-xs text-zinc-500">{{ ucfirst($entry->type) }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="edit({{ $entry->id }})" class="text-xs text-zinc-500">Edit</button>
                            <button type="button" wire:click="delete({{ $entry->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-6 text-center text-sm text-zinc-400">No entries yet.</li>
                @endforelse
            </x-slot>
        </x-admin.tables.data-table>

        @if($entries->hasPages())
            <div class="px-1">{{ $entries->links() }}</div>
        @endif
    @endif

    {{-- Sync Events modal --}}
    @if($showSyncModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-zinc-950/40" wire:click="$set('showSyncModal', false)"></div>
            <div class="relative z-10 w-full max-w-md rounded-xl bg-white shadow-xl ring-1 ring-zinc-200">
                <div class="p-6">
                    <h3 class="text-base font-semibold text-zinc-950 mb-1">Sync Events into Calendar</h3>
                    <p class="text-sm text-zinc-500 mb-1">
                        Pull all active Events for <span class="font-semibold text-zinc-700">{{ $currentCalendar->year }}</span> and add them as entries in this calendar.
                    </p>
                    <p class="text-xs text-zinc-400">Entries with a matching title and date will be updated, not duplicated.</p>
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-zinc-100 px-6 py-4">
                    <button type="button" wire:click="$set('showSyncModal', false)"
                            class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-4 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                        Cancel
                    </button>
                    <button type="button" wire:click="syncEvents"
                            class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-4 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                        Sync Events
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

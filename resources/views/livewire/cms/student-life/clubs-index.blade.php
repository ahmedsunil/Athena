<div class="space-y-4">

    @include('layouts.partials.cms-student-life-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit club' : 'Add club' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Name --}}
            <div>
                <label class="mb-1.5 block admin-label">Name <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" placeholder="e.g. Science & Technology Club"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="What does this club do?"></textarea>
                @error('description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Meeting schedule --}}
            <div>
                <label class="mb-1.5 block admin-label">Meeting schedule <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="meeting_schedule" placeholder="e.g. Thursdays, 2:30 – 4:00 PM"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('meeting_schedule') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Patron --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Patron name <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="patron_name" placeholder="e.g. Mr. Adeyemi Olawale"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('patron_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Patron role <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="patron_role" placeholder="e.g. Physics Teacher"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('patron_role') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- President --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">President name <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="president_name" placeholder="e.g. Temi Okafor"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('president_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">President class <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="president_class" placeholder="e.g. SSS 2A"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('president_class') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Logo --}}
            <div>
                <label class="mb-1.5 block admin-label">Logo <span class="text-zinc-400">(optional)</span></label>
                @if($logo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 rounded-xl object-cover">
                        <button type="button" wire:click="removeLogo"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_logo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ Storage::url($existing_logo) }}" alt="Logo" class="h-12 w-12 rounded-xl object-cover">
                        <button type="button" wire:click="removeLogo"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="logo" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('logo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $editingId ? 'Update club' : 'Add club' }}
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

    {{-- Clubs list --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Logo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Schedule</th>
            <th class="admin-table-heading">Patron</th>
            <th class="admin-table-heading">President</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($clubs as $club)
                <tr>
                    <td class="admin-table-cell">
                        @if($club->logo_path)
                            <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="h-8 w-8 rounded-lg object-cover">
                        @else
                            <div class="h-8 w-8 rounded-lg bg-zinc-100 flex items-center justify-center text-[10px] font-black text-zinc-500">
                                {{ $club->initials }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $club->name }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $club->meeting_schedule ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $club->patron_name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">
                        @if($club->president_name)
                            {{ $club->president_name }}
                            @if($club->president_class)
                                <span class="text-zinc-400">· {{ $club->president_class }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $club->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $club->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $club->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $club->id }})" wire:confirm="Delete this club?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No clubs yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($clubs as $club)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($club->logo_path)
                        <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="h-9 w-9 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="h-9 w-9 rounded-lg bg-zinc-100 flex-shrink-0 flex items-center justify-center text-[10px] font-black text-zinc-500">
                            {{ $club->initials }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $club->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $club->meeting_schedule ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $club->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $club->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No clubs yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

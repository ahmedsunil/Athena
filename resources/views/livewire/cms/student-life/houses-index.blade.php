<div class="space-y-4">

    @include('layouts.partials.cms-student-life-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit house' : 'Add house' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Name + Colour --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name_en" placeholder="e.g. Eagle House"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Name (ދިވެހި)</label>
                    <input type="text" wire:model="name_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Colour <span class="text-red-500">*</span></label>
                    <select wire:model="colour"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="rose">Rose</option>
                        <option value="sky">Sky</option>
                        <option value="emerald">Emerald</option>
                        <option value="amber">Amber</option>
                    </select>
                    @error('colour') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Motto --}}
            <div>
                <label class="mb-1.5 block admin-label">Motto (English) <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="motto_en" placeholder="e.g. Soar High, Aim Higher"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('motto_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Motto (ދިވެހި)</label>
                <input type="text" wire:model="motto_dv" dir="rtl"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('motto_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Describe the house..."></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Description (ދިވެހި)</label>
                <textarea wire:model="description_dv" rows="3" dir="rtl"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- House Master --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">House master name <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="house_master_name" placeholder="e.g. Mr. Adeyemi Olawale"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('house_master_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">House master role (English) <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="house_master_role_en" placeholder="e.g. House Master"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('house_master_role_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">House master role (ދިވެހި)</label>
                    <input type="text" wire:model="house_master_role_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('house_master_role_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Captain --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Captain name <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="captain_name" placeholder="e.g. Emeka Okonkwo"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('captain_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Captain class <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="captain_class" placeholder="e.g. SSS 3A"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('captain_class') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
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
                    {{ $editingId ? 'Update house' : 'Add house' }}
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

    {{-- Houses list --}}
    @php
    $colourSwatches = [
        'rose'    => 'bg-[#002366]',
        'sky'     => 'bg-sky-500',
        'emerald' => 'bg-emerald-500',
        'amber'   => 'bg-amber-500',
    ];
    @endphp
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Colour</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Motto</th>
            <th class="admin-table-heading">House Master</th>
            <th class="admin-table-heading">Captain</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($houses as $house)
                <tr>
                    <td class="admin-table-cell">
                        <span class="inline-block h-4 w-4 rounded-full {{ $colourSwatches[$house->colour] ?? 'bg-slate-400' }}"></span>
                    </td>
                    <td class="admin-table-cell-primary">{{ $house->name }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500 italic">{{ $house->motto ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $house->house_master_name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">
                        @if($house->captain_name)
                            {{ $house->captain_name }}
                            @if($house->captain_class)
                                <span class="text-zinc-400">· {{ $house->captain_class }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $house->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $house->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $house->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $house->id }})" wire:confirm="Delete this house?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No houses yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($houses as $house)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="h-8 w-8 rounded-full flex-shrink-0 {{ $colourSwatches[$house->colour] ?? 'bg-slate-400' }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $house->name }}</p>
                        <p class="truncate text-xs text-zinc-500 italic">{{ $house->motto ?? '—' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $house->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $house->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No houses yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

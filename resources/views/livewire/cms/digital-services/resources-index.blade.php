<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit resource' : 'Add resource' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title_en" placeholder="e.g. Google Classroom"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Title (ދިވެހި)</label>
                <input type="text" wire:model="title_dv" dir="rtl"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Brief description of the resource..."></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Description (ދިވެހި)</label>
                <textarea wire:model="description_dv" rows="3" dir="rtl"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- URL --}}
            <div>
                <label class="mb-1.5 block admin-label">URL <span class="text-red-500">*</span></label>
                <input type="url" wire:model="url" placeholder="https://..."
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('url') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Audience + Icon + Icon colour --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Audience <span class="text-red-500">*</span></label>
                    <select wire:model="audience"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($audiences as $aud)
                            <option value="{{ $aud }}">{{ $aud }}</option>
                        @endforeach
                    </select>
                    @error('audience') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Icon <span class="text-red-500">*</span></label>
                    <select wire:model="icon"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($icons as $ic)
                            <option value="{{ $ic }}">{{ $ic }}</option>
                        @endforeach
                    </select>
                    @error('icon') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Icon colour <span class="text-red-500">*</span></label>
                    <select wire:model="icon_color"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($iconColors as $ic)
                            <option value="{{ $ic }}">{{ ucfirst($ic) }}</option>
                        @endforeach
                    </select>
                    @error('icon_color') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $editingId ? 'Update resource' : 'Add resource' }}
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

    {{-- Resources list --}}
    @php
    $colorDots = [
        'sky'     => 'bg-sky-500',
        'rose'    => 'bg-rose-500',
        'emerald' => 'bg-emerald-500',
        'amber'   => 'bg-amber-500',
        'violet'  => 'bg-violet-500',
        'slate'   => 'bg-slate-500',
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
            <th class="admin-table-heading">Icon</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Audience</th>
            <th class="admin-table-heading">URL</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($resources as $resource)
                <tr>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-1.5">
                            <span class="inline-block h-2.5 w-2.5 rounded-full {{ $colorDots[$resource->icon_color] ?? 'bg-slate-400' }}"></span>
                            <span class="text-xs text-zinc-500">{{ $resource->icon }}</span>
                        </div>
                    </td>
                    <td class="admin-table-cell-primary">
                        {{ $resource->title }}
                        @if($resource->description)
                            <span class="block text-xs text-zinc-400 font-normal truncate max-w-xs">{{ Str::limit($resource->description, 60) }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $audColors[$resource->audience] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ $resource->audience }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-400 max-w-xs truncate">{{ $resource->url }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $resource->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $resource->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $resource->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $resource->id }})" wire:confirm="Delete this resource?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No resources yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($resources as $resource)
                <li class="flex items-center gap-3 px-4 py-3">
                    <span class="h-3 w-3 flex-shrink-0 rounded-full {{ $colorDots[$resource->icon_color] ?? 'bg-slate-400' }}"></span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $resource->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $resource->audience }} · {{ $resource->icon }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $resource->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $resource->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No resources yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

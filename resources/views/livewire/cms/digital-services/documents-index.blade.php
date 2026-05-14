<div class="space-y-4">

    @include('layouts.partials.cms-digital-services-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit document' : 'Add document' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title" placeholder="e.g. Student Handbook 2024–2025"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Audience --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Category <span class="text-red-500">*</span></label>
                    <select wire:model="category"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
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
            </div>

            {{-- File type + File size + Published at --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">File type <span class="text-red-500">*</span></label>
                    <select wire:model="file_type"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($fileTypes as $ft)
                            <option value="{{ $ft }}">{{ $ft }}</option>
                        @endforeach
                    </select>
                    @error('file_type') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">File size <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="file_size" placeholder="e.g. 245 KB"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('file_size') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Published date <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="published_at"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('published_at') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- File upload --}}
            <div>
                <label class="mb-1.5 block admin-label">File <span class="text-zinc-400">(optional)</span></label>
                @if($existing_file && !$fileRemoved)
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-xs text-zinc-600 truncate">{{ basename($existing_file) }}</span>
                        <button type="button" wire:click="removeFile"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                    </div>
                @endif
                <input type="file" wire:model.live="file"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('file') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $editingId ? 'Update document' : 'Add document' }}
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

    {{-- Documents list --}}
    @php
    $ftColors = [
        'PDF'  => 'bg-rose-100 text-rose-700',
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
                        <button wire:click="edit({{ $doc->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
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
                        <button wire:click="edit({{ $doc->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $doc->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No documents yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

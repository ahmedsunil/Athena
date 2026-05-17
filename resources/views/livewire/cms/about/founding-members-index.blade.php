<div class="space-y-4">

    @include('layouts.partials.cms-about-tabs')

    <div>
        <h1 class="admin-page-title">Founding Teachers</h1>
        <p class="admin-muted">Pioneer educators honoured on the History tab.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit founding member' : 'Add founding member' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Hassan Ali"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Name (ދިވެހި)</label>
                    <input type="text" wire:model="name_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Subject(s) (English)</label>
                    <input type="text" wire:model="subject_en" placeholder="e.g. Mathematics &amp; Dhivehi"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('subject_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Subject(s) (ދިވެހި)</label>
                    <input type="text" wire:model="subject_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('subject_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Tribute / Notes (English)</label>
                <textarea wire:model="tribute_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('tribute_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Tribute / Notes (ދިވެހި)</label>
                <textarea wire:model="tribute_dv" rows="3" dir="rtl"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('tribute_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Photo</label>
                @if($photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\FoundingMember::resolvePhotoUrl($existing_photo) }}" alt="" class="h-16 w-16 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="photo" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="sort_order" min="0"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update' : 'Add founding member' }}
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
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Subject</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($members as $m)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $m->id }}</td>
                    <td class="admin-table-cell">
                        @if($m->photo_path)
                            <img src="{{ $m->photo_url }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($m->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $m->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $m->subject }}</td>
                    <td class="admin-table-cell text-zinc-400">{{ $m->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $m->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete this member?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No founding members yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($members as $m)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($m->photo_path)
                        <img src="{{ $m->photo_url }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $m->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $m->subject }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $m->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>

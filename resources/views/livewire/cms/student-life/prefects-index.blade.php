<div class="space-y-4">

    @include('layouts.partials.cms-student-life-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit prefect' : 'Add prefect' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Name + Role --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Chukwuemeka Obi"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Role <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="role" placeholder="e.g. Head Boy"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('role') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Class --}}
            <div>
                <label class="mb-1.5 block admin-label">Class <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="class_name" placeholder="e.g. SSS 3A"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('class_name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Quote --}}
            <div>
                <label class="mb-1.5 block admin-label">Quote <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="quote" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="A short inspiring quote..."></textarea>
                @error('quote') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Photo --}}
            <div>
                <label class="mb-1.5 block admin-label">Photo <span class="text-zinc-400">(optional)</span></label>
                @if($photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_photo)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ Storage::url($existing_photo) }}" alt="Photo" class="h-12 w-12 rounded-full object-cover">
                        <button type="button" wire:click="removePhoto"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="photo" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $editingId ? 'Update prefect' : 'Add prefect' }}
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

    {{-- Prefects list --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Photo</th>
            <th class="admin-table-heading">Name</th>
            <th class="admin-table-heading">Role</th>
            <th class="admin-table-heading">Class</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($prefects as $prefect)
                <tr>
                    <td class="admin-table-cell">
                        @if($prefect->photo_path)
                            <img src="{{ $prefect->photo_url }}" alt="{{ $prefect->name }}" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-[10px] font-black {{ $prefect->role_colour }}">
                                {{ $prefect->initials }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $prefect->name }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $prefect->role_colour }}">
                            {{ $prefect->role }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $prefect->class_name ?? '—' }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $prefect->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $prefect->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $prefect->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $prefect->id }})" wire:confirm="Delete this prefect?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No prefects yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($prefects as $prefect)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($prefect->photo_path)
                        <img src="{{ $prefect->photo_url }}" alt="{{ $prefect->name }}" class="h-9 w-9 rounded-full object-cover flex-shrink-0">
                    @else
                        <div class="h-9 w-9 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $prefect->role_colour }}">
                            {{ $prefect->initials }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $prefect->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $prefect->role }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $prefect->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $prefect->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No prefects yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

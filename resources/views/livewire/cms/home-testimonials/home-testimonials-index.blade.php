<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Testimonials</h1>
        <p class="admin-muted">Alumni and parent testimonials shown on the home page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit testimonial' : 'Add testimonial' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name</label>
                    <input type="text" wire:model="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Photo</label>
                    @if($existing_photo)
                        <img src="{{ Storage::url($existing_photo) }}" alt="" class="mb-1 h-10 w-10 rounded-full object-cover">
                    @endif
                    <input type="file" wire:model="photo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Previous designation</label>
                    <input type="text" wire:model="previous_designation" placeholder="e.g. Student, Class of 2015"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('previous_designation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Current designation</label>
                    <input type="text" wire:model="current_designation" placeholder="e.g. Software Engineer"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('current_designation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Message</label>
                <textarea wire:model="message" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('message') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
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
                    {{ $editingId ? 'Update' : 'Add testimonial' }}
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
            <th class="admin-table-heading">Designation</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Order</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($testimonials as $t)
                <tr>
                    <td class="admin-table-cell text-zinc-400 whitespace-nowrap">#{{ $t->id }}</td>
                    <td class="admin-table-cell">
                        @if($t->photo_path)
                            <img src="{{ Storage::url($t->photo_path) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                                {{ strtoupper(substr($t->name, 0, 1)) }}
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $t->name }}</td>
                    <td class="admin-table-cell text-zinc-500 text-xs">{{ $t->current_designation }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $t->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $t->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-400">{{ $t->sort_order }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $t->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $t->id }})" wire:confirm="Delete this testimonial?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No testimonials yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($testimonials as $t)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($t->photo_path)
                        <img src="{{ Storage::url($t->photo_path) }}" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-zinc-100 text-xs font-medium text-zinc-500">
                            {{ strtoupper(substr($t->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $t->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $t->current_designation }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $t->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $t->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>

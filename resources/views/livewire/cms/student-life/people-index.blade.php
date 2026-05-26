<div class="space-y-4">

    @include('layouts.partials.cms-student-life-tabs')

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit person' : 'Add person' }}</h3>
            <p class="admin-muted">Attach yearly people history to clubs, houses, and uniform bodies.</p>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 lg:grid-cols-4">
                <div>
                    <label class="mb-1.5 block admin-label">Section</label>
                    <select wire:model.live="type"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="clubs">Clubs</option>
                        <option value="houses">Houses</option>
                        <option value="uniform-bodies">Uniform Bodies</option>
                    </select>
                    @error('type') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Item</label>
                    <select wire:model="item_id"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="">Select item</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('item_id') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year</label>
                    <input type="number" wire:model="year" min="1900" max="2100"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('sort_order') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Name</label>
                    <input type="text" wire:model="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Designation</label>
                    <input type="text" wire:model="designation" placeholder="Teacher in charge, President, Captain..."
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('designation') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Grade <span class="text-zinc-400">(not used for teacher in charge)</span></label>
                    <input type="text" wire:model="grade" placeholder="Grade 10A"
                           @disabled($is_teacher_in_charge)
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 disabled:bg-zinc-100 disabled:text-zinc-400">
                    @error('grade') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1fr_auto_auto] lg:items-end">
                <div>
                    <label class="mb-1.5 block admin-label">Avatar <span class="text-zinc-400">(optional)</span></label>
                    <div class="flex items-center gap-3">
                        @if($avatar)
                            <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar preview" class="h-12 w-12 rounded-full object-cover">
                        @elseif($existing_avatar)
                            <img src="{{ Storage::url($existing_avatar) }}" alt="Avatar" class="h-12 w-12 rounded-full object-cover">
                        @endif
                        <input type="file" wire:model.live="avatar" accept="image/*"
                               class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                        @if($avatar || $existing_avatar)
                            <button type="button" wire:click="removeAvatar" class="text-xs font-medium text-red-500 hover:text-red-700">Remove</button>
                        @endif
                    </div>
                    @error('avatar') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>

                <label class="flex h-9 items-center gap-2 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm">
                    <input type="checkbox" wire:model.live="is_teacher_in_charge"
                           class="h-4 w-4 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                    Teacher in charge
                </label>

                <label class="flex h-9 items-center gap-2 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm">
                    <input type="checkbox" wire:model="is_active"
                           class="h-4 w-4 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                    Active/current
                </label>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update person' : 'Add person' }}
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
            <th class="admin-table-heading">Person</th>
            <th class="admin-table-heading">Section</th>
            <th class="admin-table-heading">Item</th>
            <th class="admin-table-heading">Year</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($people as $person)
                <tr>
                    <td class="admin-table-cell">
                        <div class="flex items-center gap-3">
                            @if($person->avatar_url)
                                <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="h-9 w-9 rounded-full object-cover">
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-[10px] font-black text-zinc-500">{{ $person->initials }}</div>
                            @endif
                            <div>
                                <p class="text-sm font-medium text-zinc-950">{{ $person->name }}</p>
                                <p class="text-xs text-zinc-500">
                                    {{ $person->designation }}
                                    @if($person->grade)
                                        · {{ $person->grade }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ str($person->personable_type)->classBasename()->headline() }}</td>
                    <td class="admin-table-cell text-xs text-zinc-600">{{ $person->personable?->name ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $person->year }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $person->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $person->is_active ? 'Active' : 'History' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $person->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $person->id }})" wire:confirm="Delete this person?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="admin-table-cell text-center text-zinc-400">No people history yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($people as $person)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($person->avatar_url)
                        <img src="{{ $person->avatar_url }}" alt="{{ $person->name }}" class="h-9 w-9 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-zinc-100 text-[10px] font-black text-zinc-500">{{ $person->initials }}</div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $person->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $person->personable?->name ?? '—' }} · {{ $person->year }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $person->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $person->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No people history yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>
</div>

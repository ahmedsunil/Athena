<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $personId ? 'Edit Person' : 'New Person' }}</h1>
        <p class="admin-muted">Attach yearly people history to clubs, houses, and uniform bodies.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
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
                    {{ $personId ? 'Update person' : 'Add person' }}
                </button>
                <a href="{{ route('cms.people') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

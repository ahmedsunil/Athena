<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $houseId ? 'Edit House' : 'New House' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name_en" placeholder="e.g. Eagle House"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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

            <div>
                <label class="mb-1.5 block admin-label">Motto (English) <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="motto_en" placeholder="e.g. Soar High, Aim Higher"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('motto_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Describe the house..."></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

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
                </div>
            </div>

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

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $houseId ? 'Update house' : 'Add house' }}
                </button>
                <a href="{{ route('cms.houses') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

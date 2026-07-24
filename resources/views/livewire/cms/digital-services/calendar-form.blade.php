<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $itemId ? 'Edit calendar' : 'Add calendar' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">

            {{-- Title + Year --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="title_en" placeholder="e.g. Academic Calendar 2026"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="year" min="2000" max="2100"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort order + Active --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Active calendar</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Yes</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">No</button>
                    </div>
                    <p class="mt-1 text-xs text-zinc-400">Only one calendar can be active at a time.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $itemId ? 'Update calendar' : 'Add calendar' }}
                </button>
                <a href="{{ route('cms.digital-services.calendars') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

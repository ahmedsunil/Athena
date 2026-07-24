<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $sectionId ? 'Edit History Section' : 'New History Section' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title (English)</label>
                    <input type="text" wire:model="title_en"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Year label <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="year_label" placeholder="e.g. 1993 – 1995"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('year_label') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Body (English)</label>
                <p class="mb-1.5 text-xs text-zinc-400">Separate paragraphs with a blank line.</p>
                <textarea wire:model="body_en" rows="6"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('body_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Sort order</label>
                <input type="number" wire:model="sort_order" min="0"
                       class="h-9 w-32 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $sectionId ? 'Update' : 'Add section' }}
                </button>
                <a href="{{ route('cms.history') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

<div class="mx-auto max-w-xl space-y-4">

    <div class="flex items-center gap-3">
        <a href="{{ route('cms.links.index') }}"
           class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div>
            <h1 class="admin-page-title">{{ $linkId ? 'Edit link' : 'New link' }}</h1>
            <p class="admin-caption">A named route used in dropdowns and quick links.</p>
        </div>
    </div>

    <form wire:submit="save" class="space-y-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <div class="space-y-4">
                <div>
                    <label class="mb-1.5 block admin-label">Name</label>
                    <input type="text" wire:model="name" placeholder="e.g. Admissions"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block admin-label">Route</label>
                    <input type="text" wire:model="route" placeholder="e.g. /admissions"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 font-mono text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('route') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('cms.links.index') }}"
               class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                {{ $linkId ? 'Update link' : 'Create link' }}
            </button>
        </div>
    </form>

</div>

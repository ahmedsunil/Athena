<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Academics</h1>
        <p class="admin-muted">Manage the overview text and curriculum line shown at the top of the Academics page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <h3 class="admin-section-title mb-4">Overview</h3>
        <form wire:submit="save" class="space-y-4">

            <div>
                <label class="mb-1.5 block admin-label">Overview text</label>
                <textarea wire:model="text" rows="5"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Describe the school's academic approach..."></textarea>
                @error('text') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Curriculum line <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="curriculum"
                       placeholder="e.g. Maldives National Curriculum · Cambridge · Pearson Edexcel"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('curriculum') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    Save overview
                </button>
            </div>

        </form>
    </div>

</div>

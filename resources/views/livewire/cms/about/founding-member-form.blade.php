<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $foundingId ? 'Edit Founding Member' : 'New Founding Member' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" placeholder="e.g. Hassan Ali"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Subject(s) (English)</label>
                    <input type="text" wire:model="subject_en" placeholder="e.g. Mathematics &amp; Dhivehi"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('subject_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Tribute / Notes (English)</label>
                <textarea wire:model="tribute_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('tribute_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $foundingId ? 'Update' : 'Add founding member' }}
                </button>
                <a href="{{ route('cms.about.founding-members') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

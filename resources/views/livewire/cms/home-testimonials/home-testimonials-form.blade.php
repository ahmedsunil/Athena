<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $itemId ? 'Edit Testimonial' : 'New Testimonial' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Name (English)</label>
                    <input type="text" wire:model="name"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('name') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Photo</label>
                    @if($existing_photo)
                        <img src="{{ \App\Models\HomeTestimonial::resolvePhotoUrl($existing_photo) }}" alt="" class="mb-1 h-10 w-10 rounded-full object-cover">
                    @endif
                    <input type="file" wire:model="photo" accept="image/*"
                           class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                    @error('photo') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Previous designation (English)</label>
                    <input type="text" wire:model="previous_designation_en" placeholder="e.g. Student, Class of 2015"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('previous_designation_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Current designation (English)</label>
                    <input type="text" wire:model="current_designation_en" placeholder="e.g. Software Engineer"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('current_designation_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Message (English)</label>
                <textarea wire:model="message_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('message_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $itemId ? 'Update' : 'Add testimonial' }}
                </button>
                <a href="{{ route('cms.home.testimonials') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

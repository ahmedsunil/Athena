<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $albumId ? 'Edit album' : 'Add album' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title_en" placeholder="e.g. Graduation Ceremony 2024"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Date --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Category <span class="text-red-500">*</span></label>
                    <select wire:model="category"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Date <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="date"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('date') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Photo count + Facebook URL --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Photo count</label>
                    <input type="number" wire:model="photo_count" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('photo_count') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Facebook album URL <span class="text-zinc-400">(optional)</span></label>
                    <input type="url" wire:model="facebook_url" placeholder="https://facebook.com/..."
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('facebook_url') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Cover image --}}
            <div>
                <label class="mb-1.5 block admin-label">Cover image <span class="text-zinc-400">(optional)</span></label>
                @if($cover_image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="h-20 w-32 rounded-lg object-cover">
                        <button type="button" wire:click="removeCover"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_cover)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ Storage::url($existing_cover) }}" alt="Cover" class="h-20 w-32 rounded-lg object-cover">
                        <button type="button" wire:click="removeCover"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="cover_image" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('cover_image') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Sort order + Active --}}
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

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $albumId ? 'Update album' : 'Add album' }}
                </button>
                <a href="{{ route('cms.gallery.index') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

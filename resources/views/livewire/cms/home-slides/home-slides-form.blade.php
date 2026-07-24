<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $itemId ? 'Edit Slide' : 'New Slide' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="mb-1.5 block admin-label">Title (English)</label>
                <input type="text" wire:model="title_en"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Description (English)</label>
                <textarea wire:model="description_en" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div>
            </div>
            <div>
                <label class="mb-1.5 block admin-label">Image</label>
                @if($image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-24 w-auto rounded-lg object-cover">
                        <button type="button" wire:click="removeImage"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\HomeSlide::resolveImageUrl($existing_image) }}" alt="Slide" class="h-24 w-auto rounded-lg object-cover">
                        <button type="button" wire:click="removeImage"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="image" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('image') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Button 1 label (English)</label>
                    <input type="text" wire:model="button_1_label_en"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('button_1_label_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <div class="mt-2">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Button 1 link</label>
                    <select wire:model="button_1_link_key"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="">None</option>
                        @foreach($linkKeys as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('button_1_link_key') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
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
                    {{ $itemId ? 'Update' : 'Add slide' }}
                </button>
                <a href="{{ route('cms.home.slides') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

</div>

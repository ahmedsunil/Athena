<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $itemId ? 'Edit document' : 'Add document' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title_en" placeholder="e.g. Student Handbook 2024–2025"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Category + Audience --}}
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
                    <label class="mb-1.5 block admin-label">Audience <span class="text-red-500">*</span></label>
                    <select wire:model="audience"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($audiences as $aud)
                            <option value="{{ $aud }}">{{ $aud }}</option>
                        @endforeach
                    </select>
                    @error('audience') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- File type + File size + Published at --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">File type <span class="text-red-500">*</span></label>
                    <select wire:model="file_type"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($fileTypes as $ft)
                            <option value="{{ $ft }}">{{ $ft }}</option>
                        @endforeach
                    </select>
                    @error('file_type') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">File size <span class="text-zinc-400">(optional)</span></label>
                    <input type="text" wire:model="file_size" placeholder="e.g. 245 KB"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('file_size') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Published date <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="published_at"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('published_at') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- File upload --}}
            <div>
                <label class="mb-1.5 block admin-label">File <span class="text-zinc-400">(optional)</span></label>
                @if($existing_file && !$fileRemoved)
                    <div class="mb-2 flex items-center gap-2">
                        <span class="text-xs text-zinc-600 truncate">{{ basename($existing_file) }}</span>
                        <button type="button" wire:click="removeFile"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">Remove</button>
                    </div>
                @endif
                <input type="file" wire:model.live="file"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('file') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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
                    {{ $itemId ? 'Update document' : 'Add document' }}
                </button>
                <a href="{{ route('cms.digital-services.documents') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

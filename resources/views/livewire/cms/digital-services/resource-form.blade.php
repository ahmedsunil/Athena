<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $itemId ? 'Edit resource' : 'Add resource' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">

            {{-- Title --}}
            <div>
                <label class="mb-1.5 block admin-label">Title (English) <span class="text-red-500">*</span></label>
                <input type="text" wire:model="title_en" placeholder="e.g. Google Classroom"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"
                          placeholder="Brief description of the resource..."></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- URL --}}
            <div>
                <label class="mb-1.5 block admin-label">URL <span class="text-red-500">*</span></label>
                <input type="url" wire:model="url" placeholder="https://..."
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('url') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Audience + Icon + Icon colour --}}
            <div class="grid gap-4 sm:grid-cols-3">
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
                <div>
                    <label class="mb-1.5 block admin-label">Icon <span class="text-red-500">*</span></label>
                    <select wire:model="icon"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($icons as $ic)
                            <option value="{{ $ic }}">{{ $ic }}</option>
                        @endforeach
                    </select>
                    @error('icon') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Icon colour <span class="text-red-500">*</span></label>
                    <select wire:model="icon_color"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($iconColors as $ic)
                            <option value="{{ $ic }}">{{ ucfirst($ic) }}</option>
                        @endforeach
                    </select>
                    @error('icon_color') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
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
                    {{ $itemId ? 'Update resource' : 'Add resource' }}
                </button>
                <a href="{{ route('cms.digital-services.resources') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>

        </form>
    </div>

</div>

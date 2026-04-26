{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  SKELETON: form.blade.php                                                    ║
║  Use for: Create and Edit pages for any resource                             ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  HOW TO USE                                                                  ║
║  1. Copy to resources/views/livewire/[resource]/[resource]-form.blade.php   ║
║  2. Replace all [PLACEHOLDER] tags                                           ║
║  3. Wire all inputs to your Livewire component properties                    ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  PLACEHOLDERS                                                                ║
║  [resource]      → route prefix, e.g. "orders", "products"                  ║
║  [Resource]      → singular title-case, e.g. "Order", "Product"             ║
║  [resourceId]    → Livewire property holding the record's ID (null = create) ║
║  [field.*]       → wire:model property names                                 ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

<div class="px-4 py-6 sm:px-6">

    {{-- ── BACK + TITLE ─────────────────────────────────────────────────────── --}}
    <div class="mb-4 flex items-center gap-3 sm:mb-6">
        <a href="{{ route('[resource].index') }}" class="text-stone-400 hover:text-stone-600">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-lg font-bold text-stone-900 sm:text-xl">
            {{ $[resourceId] ? 'Edit [Resource] #' . $[resourceId] : 'New [Resource]' }}
        </h1>
    </div>

    {{-- ── FORM ─────────────────────────────────────────────────────────────── --}}
    <form wire:submit="save">
        {{--
            Layout: Left column (main fields, lg:col-span-2) +
                    Right column (sidebar details, 1 col)
            On smaller screens both columns stack vertically.
        --}}
        <div class="grid gap-6 lg:grid-cols-3">

            {{-- ── LEFT: MAIN CONTENT ────────────────────────────────────── --}}
            <div class="space-y-4 lg:col-span-2">

                {{-- Section 1: Core details --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold text-stone-900">[Resource] Details</h2>

                    <div class="space-y-4">

                        {{-- Field: Name / Title --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-stone-700">Name *</label>
                            <input type="text" wire:model="[field.name]"
                                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                            @error('[field.name]') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Field: Description (optional) --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-stone-700">Description</label>
                            <textarea wire:model="[field.description]" rows="3"
                                      class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
                                      placeholder="Optional description..."></textarea>
                        </div>

                        {{-- 2-column row: Price + Category (or any 2 related fields) --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-stone-700">Amount *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-stone-400">MVR</span>
                                    <input type="number" wire:model="[field.amount]" min="0" step="0.01"
                                           class="w-full rounded-lg border border-stone-300 py-2 pl-11 pr-3 text-sm font-mono text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                </div>
                                @error('[field.amount]') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1.5 block text-xs font-medium text-stone-700">Category *</label>
                                <select wire:model="[field.category]"
                                        class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                    <option value="">Select category</option>
                                    {{-- Add your options here --}}
                                </select>
                                @error('[field.category]') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Section 2: Image / File upload (remove if not needed) --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold text-stone-900">Image</h2>
                    <div class="space-y-3">
                        @if($[field.image])
                            <div class="flex items-center gap-3">
                                <img src="{{ $[field.image]->temporaryUrl() }}" alt="Preview"
                                     class="h-20 w-20 rounded-lg border border-stone-200 object-cover">
                                <div>
                                    <p class="text-xs font-medium text-stone-700">New image selected</p>
                                    <button type="button" wire:click="$set('[field.image]', null)"
                                            class="mt-1 text-xs text-red-500 hover:text-red-700">Remove</button>
                                </div>
                            </div>
                        @elseif($existingImage)
                            <div class="flex items-center gap-3">
                                <img src="{{ Storage::url($existingImage) }}" alt="Current image"
                                     class="h-20 w-20 rounded-lg border border-stone-200 object-cover">
                                <p class="text-xs text-stone-500">Current image</p>
                            </div>
                        @endif
                        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-dashed border-stone-300 p-4 text-sm text-stone-500 transition-colors hover:border-teal-400 hover:text-teal-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            <span>{{ $[field.image] ? 'Change image' : 'Upload image' }}</span>
                            <input type="file" wire:model="[field.image]" accept="image/*" class="hidden">
                        </label>
                        @error('[field.image]') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <p class="text-xs text-stone-400">JPG, PNG or WebP · max 2MB</p>
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: SIDEBAR DETAILS ────────────────────────────────── --}}
            <div class="space-y-4">

                {{-- Sidebar card: settings / metadata --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold text-stone-900">Details</h2>

                    <div class="space-y-3">

                        {{-- Status select --}}
                        <div>
                            <label class="mb-1.5 block text-xs font-medium text-stone-700">Status</label>
                            <select wire:model="[field.status]"
                                    class="w-full rounded-lg border border-stone-300 bg-white px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        {{-- Visibility toggle --}}
                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" wire:model="[field.isVisible]"
                                   class="mt-0.5 h-4 w-4 rounded border-stone-300 text-teal-600 focus:ring-teal-500">
                            <div>
                                <p class="text-sm font-medium text-stone-700">Visible</p>
                                <p class="text-xs text-stone-400">Show this record publicly</p>
                            </div>
                        </label>

                    </div>
                </div>

                {{-- Totals / summary panel (remove if not needed) --}}
                <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-sm text-stone-500">
                            <span>Subtotal</span>
                            <span class="font-mono">{{ $subtotal }}</span>
                        </div>
                        <div class="flex justify-between border-t border-stone-100 pt-2 text-base font-bold text-stone-900">
                            <span>Total</span>
                            <span class="font-mono">{{ $total }}</span>
                        </div>
                    </div>
                </div>

                {{-- Submit button --}}
                <button type="submit"
                        class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700 active:scale-95">
                    {{ $[resourceId] ? 'Update [Resource]' : 'Create [Resource]' }}
                </button>

                {{-- Cancel --}}
                <a href="{{ route('[resource].index') }}"
                   class="block w-full rounded-lg border border-stone-200 px-4 py-2.5 text-center text-sm font-medium text-stone-600 transition-colors hover:bg-stone-50">
                    Cancel
                </a>

            </div>
        </div>
    </form>

</div>

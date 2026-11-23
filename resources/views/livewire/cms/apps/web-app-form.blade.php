<div class="mx-auto max-w-2xl space-y-4">

    <div>
        <h1 class="admin-page-title">{{ $appId ? 'Edit App' : 'New App' }}</h1>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title</label>
                    <input type="text" wire:model="title"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">URL</label>
                    <input type="url" wire:model="url" placeholder="https://example.com"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('url') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Subtitle <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="subtitle" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('subtitle') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Icon</label>
                    <select wire:model="icon_key"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($iconKeys as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('icon_key') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Action button label</label>
                    <input type="text" wire:model="action_label"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('action_label') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Icon image <span class="text-zinc-400">(optional, replaces the icon above)</span></label>
                @if($icon_image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $icon_image->temporaryUrl() }}" alt="Icon preview" class="h-16 w-16 rounded-xl border border-zinc-200 object-contain p-1">
                        <button type="button" wire:click="removeIcon"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_icon_path)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ Storage::disk('public')->url($existing_icon_path) }}" alt="Current icon" class="h-16 w-16 rounded-xl border border-zinc-200 object-contain p-1">
                        <button type="button" wire:click="removeIcon"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="icon_image" accept="image/png,image/jpeg,image/webp"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                <div wire:loading wire:target="icon_image" class="mt-1 text-xs text-zinc-500">Uploading…</div>
                <p class="mt-1 text-xs text-zinc-400">PNG, JPG or WebP, up to 2 MB. Square images work best.</p>
                @error('icon_image') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-32 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" wire:model="is_active"
                               class="rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                        <span class="text-sm text-zinc-600">Active</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $appId ? 'Update' : 'Add app' }}
                </button>
                <a href="{{ route('cms.apps') }}" wire:navigate
                   class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <h3 class="admin-section-title mb-2">Preview</h3>
        <p class="admin-muted text-xs mb-3">How this app card will appear on the public page.</p>
        <div class="flex items-center gap-4 rounded-lg border border-zinc-200 bg-white p-4 shadow-sm">
            <span class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-zinc-100 text-zinc-600">
                @if($icon_image)
                    <img src="{{ $icon_image->temporaryUrl() }}" alt="" class="h-full w-full object-contain">
                @elseif($existing_icon_path)
                    <img src="{{ Storage::disk('public')->url($existing_icon_path) }}" alt="" class="h-full w-full object-contain">
                @else
                    {!! svg_icon($icon_key, 'h-6 w-6') !!}
                @endif
            </span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-zinc-950">{{ $title ?: 'App Title' }}</p>
                <p class="text-xs text-zinc-500">{{ $subtitle ?: 'App description or subtitle' }}</p>
            </div>
            <span class="inline-flex h-8 shrink-0 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white">{{ $action_label ?: 'Login' }}</span>
        </div>
    </div>

</div>

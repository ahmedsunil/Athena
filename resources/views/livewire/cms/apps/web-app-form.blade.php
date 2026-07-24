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
            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-zinc-100 text-zinc-600">{!! svg_icon($icon_key, 'h-6 w-6') !!}</span>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-zinc-950">{{ $title ?: 'App Title' }}</p>
                <p class="text-xs text-zinc-500">{{ $subtitle ?: 'App description or subtitle' }}</p>
            </div>
            <span class="inline-flex h-8 shrink-0 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white">{{ $action_label ?: 'Login' }}</span>
        </div>
    </div>

</div>

{{--
    UI: Modal
    ---------
    Centered modal overlay. Dismisses when clicking outside (wire:click.self).
    Controlled by a Livewire boolean property.

    Usage:
        @if($showModal)
            <x-admin.ui.modal title="New Customer" close-action="closeModal">
                <p class="admin-body-muted">Modal body content here.</p>

                <x-slot name="footer">
                    <button wire:click="save" class="flex-1 rounded-lg bg-zinc-900 py-2 admin-button-label text-white hover:bg-zinc-800">
                        Save
                    </button>
                    <button wire:click="closeModal" class="flex-1 rounded-lg border border-zinc-200 py-2 text-sm font-medium leading-5 text-zinc-600 hover:bg-zinc-50">
                        Cancel
                    </button>
                </x-slot>
            </x-admin.ui.modal>
        @endif

    Props:
        $title        (required) — modal heading
        $closeAction  (optional) — Livewire method name called when clicking backdrop (default: closeModal)
        $footer       (optional slot) — row of action buttons
--}}
@props(['title', 'closeAction' => 'closeModal'])

<div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/30 backdrop-blur-sm"
     wire:click.self="{{ $closeAction }}">
    <div class="w-full max-w-sm rounded-2xl border border-zinc-200 bg-white p-6 shadow-xl">

        <h2 class="admin-section-title mb-4">{{ $title }}</h2>

        <div>{{ $slot }}</div>

        @if(isset($footer))
            <div class="mt-4 flex gap-2">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

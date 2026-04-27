{{--
    UI: ConfirmDialog
    -----------------
    Delete confirmation modal with warning icon, label, and confirm/cancel buttons.
    Pairs with a Livewire trait that exposes:
        $showDeleteModal     bool
        $deleteTargetLabel   string|null
        confirmDelete($id, $label)
        executeDelete()
        cancelDelete()

    Usage:
        {{-- Include once at the bottom of any Livewire view that needs delete confirmation --}}
        @include('admin-system.components.ui.confirm-dialog')

        {{-- Or as a component: --}}
        <x-admin.ui.confirm-dialog />

    The modal is shown when $showDeleteModal === true on the parent Livewire component.
--}}

@if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/40 backdrop-blur-sm">
        <div class="w-full max-w-sm rounded-2xl border border-stone-200 bg-white p-6 shadow-xl">

            {{-- Warning icon --}}
            <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-full bg-red-50">
                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>

            <h3 class="mb-1 text-base font-semibold text-stone-900">Confirm Delete</h3>
            <p class="mb-5 text-sm text-stone-500">
                @if($deleteTargetLabel)
                    Are you sure you want to delete
                    <span class="font-medium text-stone-700">{{ $deleteTargetLabel }}</span>?
                    This action cannot be undone.
                @else
                    Are you sure you want to delete this item? This action cannot be undone.
                @endif
            </p>

            <div class="flex gap-2">
                <button wire:click="executeDelete"
                        class="flex-1 rounded-lg bg-red-600 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700 active:scale-95">
                    Delete
                </button>
                <button wire:click="cancelDelete"
                        class="flex-1 rounded-lg border border-stone-200 py-2 text-sm font-medium text-stone-600 transition-colors hover:bg-stone-50">
                    Cancel
                </button>
            </div>
        </div>
    </div>
@endif

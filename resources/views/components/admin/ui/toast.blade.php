{{--
    UI: Toast
    ---------
    Auto-dismissing toast notification stack (bottom-right).
    Listens for a custom 'toast' browser event dispatched from Livewire or a <script> tag.

    Usage:
        {{-- Place once in your layout, just before </body> --}}
        <x-admin.ui.toast />

        {{-- Fire from Livewire PHP: --}}
        $this->dispatch('toast', message: 'Saved!', type: 'success');
        $this->dispatch('toast', message: 'Something went wrong.', type: 'error');

        {{-- Fire from Blade (post-redirect flash): --}}
        session()->flash('toast', 'Customer created.');
        session()->flash('toast_type', 'success');

        {{-- Fire from JavaScript: --}}
        window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: 'Done!', type: 'success' }
        }));

    Types: 'success' (zinc) | 'error' (red)
    Auto-dismisses after 3.5 seconds.
--}}
<div x-data="{
        toasts: [],
        show(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.dismiss(id), 3500);
        },
        dismiss(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
     }"
     @toast.window="show($event.detail.message, $event.detail.type)"
     class="fixed bottom-4 right-4 z-[200] flex flex-col gap-2 pointer-events-none">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             :class="toast.type === 'error' ? 'bg-red-600' : 'bg-zinc-900'"
             class="flex items-center gap-2.5 rounded-lg px-4 py-2.5 admin-button-label text-white shadow-lg pointer-events-auto min-w-[200px]">

            {{-- Success checkmark --}}
            <svg x-show="toast.type !== 'error'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            {{-- Error X --}}
            <svg x-show="toast.type === 'error'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>

            <span x-text="toast.message" class="flex-1"></span>

            <button @click="dismiss(toast.id)" class="ml-auto opacity-70 hover:opacity-100">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

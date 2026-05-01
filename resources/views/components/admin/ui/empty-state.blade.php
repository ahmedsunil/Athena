{{--
    UI: EmptyState
    --------------
    Centered empty-state block shown when a list has no records.

    Usage:
        <x-admin.ui.empty-state message="No sales yet." />

        {{-- With custom icon --}}
        <x-admin.ui.empty-state message="No products found.">
            <x-slot name="icon">
                <svg class="mx-auto mb-3 h-8 w-8 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
            </x-slot>
        </x-admin.ui.empty-state>

    Props:
        $message  (required) — text shown below the icon
        $icon     (optional slot) — custom SVG; uses a generic box icon by default
--}}
@props(['message'])

<div class="py-12 text-center">
    @if(isset($icon))
        {{ $icon }}
    @else
        <svg class="mx-auto mb-3 h-8 w-8 text-zinc-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    @endif
    <p class="text-sm font-medium text-zinc-400">{{ $message }}</p>
</div>

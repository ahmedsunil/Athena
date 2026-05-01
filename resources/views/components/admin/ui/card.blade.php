{{--
    UI: Card
    --------
    White rounded container with border and shadow. The base surface for all content sections.

    Usage:
        {{-- Standard card --}}
        <x-admin.ui.card>
            Content here
        </x-admin.ui.card>

        {{-- With a section header --}}
        <x-admin.ui.card title="Product Details">
            Content here
        </x-admin.ui.card>

        {{-- Compact (less vertical padding) --}}
        <x-admin.ui.card compact>
            Content here
        </x-admin.ui.card>

    Props:
        $title    (optional) — renders a small header above the slot
        $compact  (optional) — uses px-5 py-4 instead of p-5
--}}
@props(['title' => null, 'compact' => false])

<div class="{{ $compact ? 'rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm' : 'rounded-xl border border-zinc-200 bg-white p-5 shadow-sm' }}">
    @if($title)
        <h2 class="admin-section-title mb-4">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>

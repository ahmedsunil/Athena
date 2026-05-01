{{--
    Layout: PageHeader
    ------------------
    Page title + optional subtitle + optional right-side action slot.

    Usage:
        {{-- Title only --}}
        <x-admin.layout.page-header title="Customers" />

        {{-- With subtitle --}}
        <x-admin.layout.page-header title="Sales" subtitle="{{ $total }} total records" />

        {{-- With action button --}}
        <x-admin.layout.page-header title="Products" subtitle="{{ $count }} products">
            <x-slot name="action">
                <a href="{{ route('products.create') }}"
                   class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-900 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Product
                </a>
            </x-slot>
        </x-admin.layout.page-header>

    Props:
        $title     (required) — main heading
        $subtitle  (optional) — secondary line below the title
        $action    (optional slot) — right-side content (usually a button)
--}}
@props(['title', 'subtitle' => null])

<div class="mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6">
    <div>
        <h1 class="text-lg font-bold text-zinc-900 sm:text-xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-xs text-zinc-500 sm:text-sm">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($action))
        <div>{{ $action }}</div>
    @endif
</div>

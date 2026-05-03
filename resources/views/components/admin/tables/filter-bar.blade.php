{{--
    Tables: FilterBar
    -----------------
    Search + filter row shown above a data table.
    Contains a search input and an optional slot for additional filters (dates, selects).
    Shows a "Clear" button when any filter is active.

    Usage:
        <x-admin.tables.filter-bar
            search-model="search"
            placeholder="Search customer..."
            :has-filters="$search || $dateFrom || $dateTo || $status"
            clear-action="clearFilters"
        >
            {{-- Extra filters go in the slot --}}
            <input type="date" wire:model.live="dateFrom"
                   class="rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-sm font-normal leading-5 text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            <span class="text-zinc-400 text-sm">–</span>
            <input type="date" wire:model.live="dateTo"
                   class="rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-sm font-normal leading-5 text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            <select wire:model.live="status"
                    class="rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-sm font-normal leading-5 text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                <option value="">All statuses</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
            </select>
        </x-admin.tables.filter-bar>

    Props:
        $searchModel  (required) — wire:model value for the search field
        $placeholder  (optional) — search input placeholder
        $hasFilters   (optional) — boolean; shows Clear button when true
        $clearAction  (optional) — Livewire method name to clear all filters. Default: clearFilters
--}}
@props([
    'searchModel' => 'search',
    'placeholder' => 'Search...',
    'hasFilters'  => false,
    'clearAction' => 'clearFilters',
])

<div class="mb-4 rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 shadow-sm ring-1 ring-zinc-100/70">
    <div class="flex flex-wrap items-center gap-2">

        {{-- Search --}}
        <div class="relative min-w-[180px] flex-1">
            <svg class="absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-zinc-400"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="{{ $searchModel }}"
                   placeholder="{{ $placeholder }}"
                   class="admin-form-control w-full rounded-lg border border-zinc-200 bg-white py-1.5 pl-8 pr-3 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
        </div>

        {{-- Extra filters (dates, selects) --}}
        {{ $slot }}

        {{-- Clear button --}}
        @if($hasFilters)
            <button wire:click="{{ $clearAction }}"
                    class="admin-link-label flex items-center gap-1 rounded-lg border border-zinc-200 bg-white px-2.5 py-1.5 text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </button>
        @endif

    </div>
</div>

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
                   class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <span class="text-stone-400 text-sm">–</span>
            <input type="date" wire:model.live="dateTo"
                   class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <select wire:model.live="status"
                    class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
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

<div class="mb-4 rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5">
    <div class="flex flex-wrap items-center gap-2">

        {{-- Search --}}
        <div class="relative min-w-[180px] flex-1">
            <svg class="absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text"
                   wire:model.live.debounce.300ms="{{ $searchModel }}"
                   placeholder="{{ $placeholder }}"
                   class="w-full rounded-lg border border-stone-200 bg-white py-1.5 pl-8 pr-3 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
        </div>

        {{-- Extra filters (dates, selects) --}}
        {{ $slot }}

        {{-- Clear button --}}
        @if($hasFilters)
            <button wire:click="{{ $clearAction }}"
                    class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-stone-500 transition-colors hover:bg-stone-200">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Clear
            </button>
        @endif

    </div>
</div>

{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  SKELETON: index.blade.php                                                   ║
║  Use for: Any resource listing page (orders, customers, products, etc.)      ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  HOW TO USE                                                                  ║
║  1. Copy to resources/views/livewire/[resource]/[resource]-list.blade.php   ║
║  2. Replace all [PLACEHOLDER] tags                                           ║
║  3. Wire up your Livewire component properties                               ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  PLACEHOLDERS                                                                ║
║  [PageTitle]       → e.g. "Orders", "Customers", "Products"                 ║
║  [resource]        → route prefix, e.g. "orders", "customers"               ║
║  [Resource]        → singular title-case, e.g. "Order", "Customer"          ║
║  [totalCount]      → Livewire property for total record count               ║
║  [records]         → Livewire property name for the paginated collection     ║
║  [Column N]        → Table column header text                                ║
║  [record.*]        → Model fields (e.g. $record->name, $record->status)     ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

<div class="px-4 py-6 sm:px-6">

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6">
        <div>
            <h1 class="text-lg font-bold text-stone-900 sm:text-xl">[PageTitle]</h1>
            <p class="text-xs text-stone-500 sm:text-sm">{{ $totalCount }} total records</p>
        </div>
        <a href="{{ route('[resource].create') }}"
           class="inline-flex items-center gap-1.5 rounded-lg bg-teal-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New [Resource]
        </a>
    </div>

    {{-- ── FILTER BAR ───────────────────────────────────────────────────────── --}}
    <div class="mb-4 rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5">
        <div class="flex flex-wrap items-center gap-2">

            {{-- Search --}}
            <div class="relative min-w-[180px] flex-1">
                <svg class="absolute left-2.5 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-stone-400"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search [resource]..."
                       class="w-full rounded-lg border border-stone-200 bg-white py-1.5 pl-8 pr-3 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            </div>

            {{-- Date range (remove if not needed) --}}
            <input type="date" wire:model.live="dateFrom"
                   class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            <span class="text-sm text-stone-400">–</span>
            <input type="date" wire:model.live="dateTo"
                   class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">

            {{-- Status filter (remove if not needed) --}}
            <select wire:model.live="statusFilter"
                    class="rounded-lg border border-stone-200 bg-white px-2.5 py-1.5 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                <option value="">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            {{-- Clear filters --}}
            @if($search || $dateFrom || $dateTo || $statusFilter)
                <button wire:click="clearFilters"
                        class="flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-stone-500 transition-colors hover:bg-stone-200">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear
                </button>
            @endif

        </div>
    </div>

    {{-- ── BULK ACTION BAR ─────────────────────────────────────────────────── --}}
    @if(count($selected) > 0)
        <div class="mb-3 flex items-center justify-between rounded-lg border border-red-100 bg-red-50 px-4 py-2.5">
            <span class="text-sm font-medium text-red-700">
                {{ count($selected) }} {{ Str::plural('[resource]', count($selected)) }} selected
            </span>
            <button wire:click="bulkDelete"
                    wire:confirm="Delete {{ count($selected) }} selected {{ Str::plural('[resource]', count($selected)) }}? This cannot be undone."
                    class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-red-700">
                Delete Selected
            </button>
        </div>
    @endif

    {{-- ── TABLE + MOBILE CARDS ────────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">

        @if($[records]->count() > 0)

            {{-- ── MOBILE CARD LIST (hidden md+) ──────────────────────────── --}}
            <ul class="divide-y divide-stone-100 md:hidden">
                @foreach($[records] as $record)
                    <li class="flex items-start gap-3 px-4 py-3 {{ in_array((string)$record->id, $selected) ? 'bg-red-50/50' : '' }}">

                        {{-- Checkbox --}}
                        <input type="checkbox" wire:model.live="selected" value="{{ $record->id }}"
                               class="mt-1 h-3.5 w-3.5 shrink-0 rounded border-stone-300 text-teal-600 focus:ring-teal-500">

                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                {{-- Left: primary info --}}
                                <a href="{{ route('[resource].show', $record) }}" class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-stone-800">{{ $record->name }}</p>
                                    <p class="mt-0.5 text-xs text-stone-500">{{ $record->created_at->format('d M Y') }}</p>
                                </a>
                                {{-- Right: value + badge --}}
                                <div class="shrink-0 text-right">
                                    <p class="text-sm font-semibold text-stone-900">{{ $record->value }}</p>
                                    <div class="mt-1 flex justify-end">
                                        <x-admin.ui.status-badge :status="$record->status" />
                                    </div>
                                </div>
                            </div>
                            {{-- Mobile actions --}}
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs font-medium">
                                <a href="{{ route('[resource].edit', $record) }}" class="text-stone-500 hover:text-stone-700">Edit</a>
                                <a href="{{ route('[resource].show', $record) }}" class="text-teal-600 hover:text-teal-700">View</a>
                                <button wire:click="confirmDelete({{ $record->id }}, '{{ $record->name }}')"
                                        class="text-red-400 hover:text-red-600">Delete</button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            {{-- ── DESKTOP TABLE (hidden below md) ────────────────────────── --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-stone-100 text-left text-xs font-medium text-stone-400">
                            {{-- Select-all checkbox --}}
                            <th class="w-10 px-4 py-3">
                                <input type="checkbox" wire:model.live="selectAll"
                                       class="h-3.5 w-3.5 rounded border-stone-300 text-teal-600 focus:ring-teal-500">
                            </th>
                            <th class="px-5 py-3">ID</th>
                            <th class="px-5 py-3">[Column 1]</th>
                            <th class="px-5 py-3">[Column 2]</th>
                            <th class="px-5 py-3">[Column 3]</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($[records] as $record)
                            <tr class="border-b border-stone-100 transition-colors odd:bg-white even:bg-stone-50/50 hover:bg-teal-50/30 last:border-0 {{ in_array((string)$record->id, $selected) ? 'bg-red-50/50' : '' }}">
                                <td class="w-10 px-4 py-3">
                                    <input type="checkbox" wire:model.live="selected" value="{{ $record->id }}"
                                           class="h-3.5 w-3.5 rounded border-stone-300 text-teal-600 focus:ring-teal-500">
                                </td>
                                <td class="px-5 py-3 font-mono text-xs text-stone-400">#{{ $record->id }}</td>
                                <td class="px-5 py-3 font-medium text-stone-800">
                                    <a href="{{ route('[resource].show', $record) }}" class="hover:text-teal-600">
                                        {{ $record->name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-stone-500">{{ $record->secondary }}</td>
                                <td class="px-5 py-3 text-stone-500">{{ $record->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <x-admin.ui.status-badge :status="$record->status" />
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('[resource].edit', $record) }}"
                                           class="text-xs font-medium text-stone-500 hover:text-stone-700">Edit</a>
                                        <a href="{{ route('[resource].show', $record) }}"
                                           class="text-xs font-medium text-teal-600 hover:text-teal-700">View</a>
                                        <button wire:click="confirmDelete({{ $record->id }}, '#{{ $record->id }}')"
                                                class="text-xs font-medium text-red-400 hover:text-red-600">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-stone-100 px-5 py-3">
                {{ $[records]->links() }}
            </div>

        @else

            {{-- ── EMPTY STATE ────────────────────────────────────────────── --}}
            <div class="py-12 text-center">
                <svg class="mx-auto mb-3 h-8 w-8 text-stone-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-sm font-medium text-stone-400">
                    @if($search || $dateFrom || $dateTo || $statusFilter)
                        No records match your filters.
                    @else
                        No [resource] yet.
                    @endif
                </p>
            </div>

        @endif
    </div>

    {{-- ── CONFIRM DELETE MODAL ────────────────────────────────────────────── --}}
    @include('admin-system.components.ui.confirm-dialog')

</div>

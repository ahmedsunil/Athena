{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  SKELETON: show.blade.php                                                    ║
║  Use for: Detail / view page for a single record                             ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  HOW TO USE                                                                  ║
║  1. Copy to resources/views/livewire/[resource]/[resource]-detail.blade.php ║
║  2. Replace all [PLACEHOLDER] tags                                           ║
║  3. Wire up your Livewire component properties                               ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  PLACEHOLDERS                                                                ║
║  [resource]   → route prefix, e.g. "orders", "sales"                        ║
║  [Resource]   → singular title-case, e.g. "Order", "Sale"                   ║
║  [record]     → Livewire property name for the model, e.g. $sale, $order    ║
║  [field.*]    → Model fields you want to display                             ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

<div class="px-4 py-6 sm:px-6">

    {{-- ── DETAIL HEADER ───────────────────────────────────────────────────── --}}
    {{-- Back arrow + title + status badge + action buttons --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

        {{-- Left: back + title --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('[resource].index') }}" class="text-stone-400 hover:text-stone-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-lg font-bold text-stone-900 sm:text-xl">
                        [Resource] #{{ $[record]->id }}
                    </h1>
                    <x-admin.ui.status-badge :status="$[record]->status" />
                </div>
                <p class="truncate text-xs text-stone-500 sm:text-sm">
                    {{ $[record]->created_at->format('d M Y') }} · {{ $[record]->relatedModel->name ?? '' }}
                </p>
            </div>
        </div>

        {{-- Right: action buttons --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Secondary action (e.g. PDF, export) --}}
            <a href="{{ route('[resource].export', $[record]) }}" target="_blank"
               class="inline-flex items-center gap-1.5 rounded-lg border border-stone-200 px-3.5 py-2 text-sm font-medium text-stone-600 transition-colors hover:bg-stone-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Export
            </a>
            {{-- Primary action: Edit --}}
            <a href="{{ route('[resource].edit', $[record]) }}"
               class="inline-flex items-center gap-1.5 rounded-lg bg-teal-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
                Edit [Resource]
            </a>
        </div>

    </div>

    {{-- ── MAIN GRID: 2/3 content + 1/3 sidebar ────────────────────────────── --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- ── LEFT: LINE ITEMS / MAIN DATA ──────────────────────────────── --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">

                {{-- Section header --}}
                <div class="border-b border-stone-100 px-5 py-3.5">
                    <h2 class="text-sm font-semibold text-stone-900">Line Items</h2>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[480px] text-sm">
                        <thead>
                            <tr class="border-b border-stone-100 text-left text-xs font-medium text-stone-400">
                                <th class="px-5 py-3">Item</th>
                                <th class="px-5 py-3 text-right">Qty</th>
                                <th class="px-5 py-3 text-right">Unit Price</th>
                                <th class="px-5 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($[record]->lineItems as $item)
                                <tr class="border-b border-stone-100 transition-colors odd:bg-white even:bg-stone-50/50 hover:bg-teal-50/30 last:border-0">
                                    <td class="px-5 py-3 font-medium text-stone-800">{{ $item->description }}</td>
                                    <td class="px-5 py-3 text-right text-stone-500">{{ $item->quantity }}</td>
                                    <td class="px-5 py-3 text-right font-mono text-stone-500">
                                        MVR {{ number_format($item->unit_price, 2) }}
                                    </td>
                                    <td class="px-5 py-3 text-right font-mono font-medium text-stone-800">
                                        MVR {{ number_format($item->subtotal, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Totals footer --}}
                <div class="border-t border-stone-100 px-5 py-4 text-sm">
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-stone-500">
                            <span>Subtotal</span>
                            <span class="font-mono">MVR {{ number_format($[record]->subtotal, 2) }}</span>
                        </div>
                        @if($[record]->discount_percentage > 0)
                            <div class="flex justify-between text-stone-500">
                                <span>Discount ({{ $[record]->discount_percentage }}%)</span>
                                <span class="font-mono text-red-500">−MVR {{ number_format($[record]->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between border-t border-stone-100 pt-2 text-base font-bold text-stone-900">
                            <span>Total</span>
                            <span class="font-mono">MVR {{ number_format($[record]->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Notes (show if present) --}}
                @if($[record]->notes)
                    <div class="border-t border-stone-100 px-5 py-3">
                        <p class="text-xs font-medium text-stone-400">Notes</p>
                        <p class="mt-1 text-sm text-stone-600">{{ $[record]->notes }}</p>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── RIGHT: DETAIL SIDEBAR ──────────────────────────────────────── --}}
        <div class="space-y-4">

            {{-- Related entity card (e.g. Customer) --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-stone-900">Related Info</h2>
                <div class="space-y-1 text-sm">
                    <p class="font-medium text-stone-800">{{ $[record]->relatedModel->name }}</p>
                    @if($[record]->relatedModel->phone)
                        <p class="text-stone-500">{{ $[record]->relatedModel->phone }}</p>
                    @endif
                    @if($[record]->relatedModel->address)
                        <p class="text-stone-500">{{ $[record]->relatedModel->address }}</p>
                    @endif
                </div>
            </div>

            {{-- Metadata card (converted from, references, etc.) --}}
            @if($[record]->reference)
                <div class="rounded-xl border border-stone-100 bg-stone-50 p-4">
                    <p class="mb-1 text-xs font-medium text-stone-400">Reference</p>
                    <a href="{{ route('[resource].show', $[record]->reference) }}"
                       class="text-sm font-medium text-teal-600 hover:underline">
                        #{{ $[record]->reference->id }}
                    </a>
                </div>
            @endif

            {{-- Quick actions card --}}
            <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
                <h2 class="mb-3 text-sm font-semibold text-stone-900">Actions</h2>
                <div class="space-y-2">
                    <a href="{{ route('[resource].edit', $[record]) }}"
                       class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-stone-600 transition-colors hover:bg-stone-50">
                        Edit record
                        <svg class="h-4 w-4 text-stone-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <button wire:click="confirmDelete({{ $[record]->id }}, '[Resource] #{{ $[record]->id }}')"
                            class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition-colors hover:bg-red-50">
                        Delete record
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    {{-- ── CONFIRM DELETE MODAL ────────────────────────────────────────────── --}}
    @include('admin-system.components.ui.confirm-dialog')

</div>

{{--
╔══════════════════════════════════════════════════════════════════════════════╗
║  SKELETON: dashboard.blade.php                                               ║
║  Use for: Main dashboard / overview page                                     ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  HOW TO USE                                                                  ║
║  1. Copy this file to resources/views/livewire/dashboard.blade.php           ║
║  2. Find all [PLACEHOLDER] tags and replace them                             ║
║  3. Wire up your Livewire component properties                               ║
╠══════════════════════════════════════════════════════════════════════════════╣
║  PLACEHOLDERS                                                                ║
║  [AppName]       → Your app name (e.g. "Veya", "Admin")                     ║
║  [StatLabel]     → Metric label (e.g. "Total Revenue", "Active Users")      ║
║  [StatValue]     → Metric value from Livewire (e.g. $totalRevenue)          ║
║  [StatSecondary] → Secondary label (e.g. "48 sales", "all time")            ║
║  [ChartLabel]    → Chart section title (e.g. "Revenue by Month")            ║
║  [TableTitle]    → Table section title (e.g. "Recent Orders")               ║
║  [TableViewAll]  → route() for "View all" link                              ║
║  [ColumnN]       → Column header text                                        ║
╚══════════════════════════════════════════════════════════════════════════════╝
--}}

<div class="px-4 py-6 sm:px-6">

    {{-- ── PAGE HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-4 sm:mb-6">
        <h1 class="text-lg font-bold text-stone-900 sm:text-xl">Dashboard</h1>
        <p class="text-xs text-stone-500 sm:text-sm">Business overview</p>
    </div>

    {{-- ── KPI STAT CARDS ──────────────────────────────────────────────────── --}}
    {{-- Grid: 1 col mobile → 3 cols sm+ --}}
    <div class="mb-6 grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-4">

        {{-- Stat Card 1 (primary / success) --}}
        <div class="flex items-center gap-4 rounded-xl border border-stone-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-teal-50">
                <svg class="h-4 w-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    {{-- Replace with your icon path --}}
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium text-stone-500">[StatLabel 1]</p>
                <p class="text-lg font-bold text-stone-900">{{ $statValue1 }}</p>
            </div>
            <span class="shrink-0 text-xs text-stone-400">{{ $statSecondary1 }}</span>
        </div>

        {{-- Stat Card 2 (warning) --}}
        <div class="flex items-center gap-4 rounded-xl border border-stone-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-50">
                <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium text-stone-500">[StatLabel 2]</p>
                <p class="text-lg font-bold text-stone-900">{{ $statValue2 }}</p>
            </div>
            <span class="shrink-0 text-xs text-stone-400">{{ $statSecondary2 }}</span>
        </div>

        {{-- Stat Card 3 (neutral) --}}
        <div class="flex items-center gap-4 rounded-xl border border-stone-200 bg-white px-5 py-4 shadow-sm">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-stone-100">
                <svg class="h-4 w-4 text-stone-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-medium text-stone-500">[StatLabel 3]</p>
                <p class="text-lg font-bold text-stone-900">{{ $statValue3 }}</p>
            </div>
            <span class="shrink-0 text-xs text-stone-400">{{ $statSecondary3 }}</span>
        </div>

    </div>

    {{-- ── CHART + SECONDARY WIDGET ─────────────────────────────────────────── --}}
    {{-- Grid: full width → lg: 2/3 chart + 1/3 widget --}}
    <div class="mb-6 grid gap-4 lg:grid-cols-3">

        {{-- Bar Chart Card (spans 2 cols on lg) --}}
        <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm lg:col-span-2">

            {{-- Chart header --}}
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="text-sm font-semibold text-stone-900">[ChartLabel]</h2>
                    <p class="text-xs text-stone-400">Description line</p>
                </div>
                {{-- Optional date range filter --}}
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="chartFrom"
                           class="rounded-lg border border-stone-300 px-2 py-1.5 text-xs text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                    <span class="text-xs text-stone-400">–</span>
                    <input type="date" wire:model.live="chartTo"
                           class="rounded-lg border border-stone-300 px-2 py-1.5 text-xs text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                </div>
            </div>

            {{-- Period summary strip --}}
            <div class="mb-4 grid grid-cols-3 divide-x divide-stone-100 rounded-lg bg-stone-50 px-1 py-2.5">
                <div class="px-3 text-center">
                    <p class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Metric 1</p>
                    <p class="mt-0.5 text-sm font-bold text-stone-900">{{ $periodTotal }}</p>
                    <p class="text-[10px] text-stone-400">{{ $periodCount }} items</p>
                </div>
                <div class="px-3 text-center">
                    <p class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Metric 2</p>
                    <p class="mt-0.5 text-sm font-bold text-teal-600">{{ $periodPaid }}</p>
                    <p class="text-[10px] text-stone-400">sub-label</p>
                </div>
                <div class="px-3 text-center">
                    <p class="text-[10px] font-medium uppercase tracking-wide text-stone-400">Rate</p>
                    <p class="mt-0.5 text-sm font-bold text-teal-600">{{ $periodRate }}%</p>
                    <p class="text-[10px] text-stone-400">of total</p>
                </div>
            </div>

            @if(count($chartData) > 0)
                @php $maxValue = max(array_column($chartData, 'total')) ?: 1; @endphp

                {{-- Bar chart --}}
                <div class="flex h-40 items-end gap-1.5">
                    @foreach($chartData as $bar)
                        @php $pct = round(($bar['total'] / $maxValue) * 100); @endphp
                        <div class="group relative flex h-full flex-1 items-end">
                            {{-- Tooltip --}}
                            <div class="absolute bottom-full left-1/2 z-10 mb-2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg border border-stone-200 bg-white px-3 py-2 text-xs shadow-lg group-hover:block">
                                <p class="mb-1 font-semibold text-stone-800">{{ $bar['label'] }}</p>
                                <p class="text-stone-500">{{ $bar['total'] }}</p>
                            </div>
                            {{-- Bar --}}
                            <div class="relative w-full overflow-hidden rounded-t-sm bg-stone-100 transition-all group-hover:bg-stone-200"
                                 style="height: {{ max($pct, 2) }}%">
                                <div class="absolute bottom-0 left-0 w-full bg-teal-500 transition-all group-hover:bg-teal-600"
                                     style="height: 100%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Month/period labels --}}
                <div class="mt-2 flex gap-1.5">
                    @foreach($chartData as $bar)
                        <div class="flex-1 truncate text-center text-[9px] text-stone-400">{{ $bar['label'] }}</div>
                    @endforeach
                </div>

                {{-- Legend --}}
                <div class="mt-3 flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-sm bg-teal-500"></span>
                        <span class="text-[10px] text-stone-400">Total</span>
                    </div>
                </div>

            @else
                <div class="flex h-40 items-center justify-center">
                    <p class="text-sm text-stone-400">No data for this period.</p>
                </div>
            @endif
        </div>

        {{-- Secondary widget (right column) --}}
        <div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
            <h2 class="mb-4 text-sm font-semibold text-stone-900">Top Items</h2>
            @if($topItems->count() > 0)
                <div class="space-y-3">
                    @foreach($topItems as $item)
                        <div>
                            <div class="mb-1 flex items-center justify-between">
                                <span class="max-w-[140px] truncate text-xs font-medium text-stone-700">
                                    {{ $item->name }}
                                </span>
                                <span class="shrink-0 text-xs text-stone-400">{{ $item->count }}</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-stone-100">
                                <div class="h-full rounded-full bg-teal-500"
                                     style="width: {{ round(($item->count / $maxItemCount) * 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-stone-400">No data yet.</p>
            @endif
        </div>

    </div>

    {{-- ── RECENT RECORDS TABLE ─────────────────────────────────────────────── --}}
    <div class="overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">

        {{-- Table header --}}
        <div class="flex items-center justify-between border-b border-stone-100 px-5 py-3.5">
            <h2 class="text-sm font-semibold text-stone-900">[TableTitle]</h2>
            <a href="{{ route('[TableViewAll]') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700">
                View all →
            </a>
        </div>

        @if($recentRecords->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-stone-100 text-left text-xs font-medium text-stone-400">
                            <th class="px-5 py-3">[Column 1]</th>
                            <th class="px-5 py-3">[Column 2]</th>
                            <th class="px-5 py-3">[Column 3]</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentRecords as $record)
                            <tr class="cursor-pointer border-b border-stone-100 transition-colors odd:bg-white even:bg-stone-50/50 hover:bg-teal-50/30 last:border-0"
                                onclick="window.location='{{ route('[resource].show', $record) }}'">
                                <td class="px-5 py-3 font-medium text-stone-800">{{ $record->name }}</td>
                                <td class="px-5 py-3 text-stone-500">{{ $record->secondary }}</td>
                                <td class="px-5 py-3 text-stone-500">{{ $record->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    <x-admin.ui.status-badge :status="$record->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="px-5 py-6 text-sm text-stone-400">No records yet.</p>
        @endif

    </div>

</div>

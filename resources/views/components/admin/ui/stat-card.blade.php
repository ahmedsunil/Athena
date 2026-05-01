{{--
    UI: StatCard
    ------------
    Metric card with a colored icon, label, value, and optional secondary text.
    Used in the dashboard KPI row.

    Usage:
        <x-admin.ui.stat-card
            label="Total Paid"
            value="MVR 12,400"
            secondary="48 sales"
            icon-color="teal"
        >
            <x-slot name="icon">
                <svg class="h-4 w-4 text-zinc-600" ...>...</svg>
            </x-slot>
        </x-admin.ui.stat-card>

    Props:
        $label       (required)  — small label above the value
        $value       (required)  — large bold value
        $secondary   (optional)  — small text to the right (count, period, etc.)
        $iconColor   (optional)  — Tailwind color name for icon bg: teal | amber | stone | red
        $icon        (slot)      — SVG icon markup
--}}
@props([
    'label'     => '',
    'value'     => '',
    'secondary' => null,
    'iconColor' => 'teal',
])

@php
    $iconBgMap = [
        'teal'   => 'bg-zinc-100',
        'amber'  => 'bg-amber-50',
        'red'    => 'bg-red-50',
        'stone'  => 'bg-zinc-100',
        'violet' => 'bg-zinc-100',
    ];
    $iconBg = $iconBgMap[$iconColor] ?? 'bg-zinc-100';
@endphp

<div class="flex items-center gap-4 rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm">
    @if(isset($icon))
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $iconBg }}">
            {{ $icon }}
        </div>
    @endif

    <div class="min-w-0 flex-1">
        <p class="admin-stat-label">{{ $label }}</p>
        <p class="admin-stat-value">{{ $value }}</p>
    </div>

    @if($secondary)
        <span class="admin-muted shrink-0">{{ $secondary }}</span>
    @endif
</div>

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

<div class="flex items-center gap-4 rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm ring-1 ring-zinc-100/70">
    @if(isset($icon))
        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg shadow-sm {{ $iconBg }}">
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

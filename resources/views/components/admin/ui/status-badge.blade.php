@props(['status', 'label' => null])

@php
    $variants = [
        'paid'     => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
        'accepted' => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
        'active'   => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
        'pending'  => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'draft'    => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'cancelled'=> 'bg-red-50 text-red-700 ring-red-600/20',
        'rejected' => 'bg-red-50 text-red-700 ring-red-600/20',
        'inactive' => 'bg-zinc-100 text-zinc-600 ring-zinc-500/20',
    ];

    $colorClasses = $variants[$status] ?? 'bg-zinc-100 text-zinc-600 ring-zinc-500/20';
    $displayLabel = $label ?? ucfirst($status);
@endphp

<span class="inline-flex items-center rounded-full px-2 py-0.5 admin-badge shadow-sm ring-1 ring-inset {{ $colorClasses }}">
    {{ $displayLabel }}
</span>

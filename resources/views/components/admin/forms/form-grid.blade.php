@props(['cols' => 2])

@php
    $gridClass = match((int)$cols) {
        1       => 'grid grid-cols-1 gap-4',
        3       => 'grid grid-cols-1 gap-4 sm:grid-cols-3',
        default => 'grid grid-cols-2 gap-4',
    };
@endphp

<div class="{{ $gridClass }}">
    {{ $slot }}
</div>

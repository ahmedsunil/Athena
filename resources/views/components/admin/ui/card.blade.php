@props(['title' => null, 'compact' => false])

<div class="{{ $compact ? 'rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm ring-1 ring-zinc-100/70' : 'rounded-xl border border-zinc-200 bg-white p-5 shadow-sm ring-1 ring-zinc-100/70' }}">
    @if($title)
        <h2 class="admin-section-title mb-4">{{ $title }}</h2>
    @endif
    {{ $slot }}
</div>

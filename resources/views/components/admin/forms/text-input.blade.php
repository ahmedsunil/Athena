{{--
    Forms: TextInput
    ----------------
    Standard text input with consistent focus styles.
--}}
@props(['type' => 'text', 'prefix' => null, 'placeholder' => ''])

@if($prefix)
    <div class="relative">
        <span class="admin-muted absolute left-3 top-1/2 -translate-y-1/2">{{ $prefix }}</span>
        <input
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class(['admin-form-control w-full rounded-lg border border-zinc-200 bg-white py-2 pl-11 pr-3 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
        >
    </div>
@else
    <input
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->class(['admin-form-control w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
    >
@endif

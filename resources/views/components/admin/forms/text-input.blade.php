{{--
    Forms: TextInput
    ----------------
    Standard text input with consistent focus styles.
--}}
@props(['type' => 'text', 'prefix' => null, 'placeholder' => ''])

@if($prefix)
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400">{{ $prefix }}</span>
        <input
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class(['w-full rounded-lg border border-zinc-300 py-2 pl-11 pr-3 text-sm text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
        >
    </div>
@else
    <input
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->class(['w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
    >
@endif

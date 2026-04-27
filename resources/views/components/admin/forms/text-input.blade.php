{{--
    Forms: TextInput
    ----------------
    Standard text input with consistent focus styles.
--}}
@props(['type' => 'text', 'prefix' => null, 'placeholder' => ''])

@if($prefix)
    <div class="relative">
        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-stone-400">{{ $prefix }}</span>
        <input
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->class(['w-full rounded-lg border border-stone-300 py-2 pl-11 pr-3 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500']) }}
        >
    </div>
@else
    <input
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->class(['w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500']) }}
    >
@endif

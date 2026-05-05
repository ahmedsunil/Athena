@props(['label' => null])

<div
    x-data="{ on: @entangle($attributes->wire('model')) }"
    class="flex cursor-pointer items-center gap-2.5"
    @click="on = !on">

    {{-- Track --}}
    <button
        type="button"
        role="switch"
        :aria-checked="on"
        :class="on ? 'bg-zinc-900' : 'bg-zinc-200'"
        class="inline-flex h-5 w-9 shrink-0 items-center rounded-full shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-950/10">
        {{-- Knob --}}
        <span
            :class="on ? 'translate-x-4' : 'translate-x-0.5'"
            class="inline-block h-3.5 w-3.5 rounded-full bg-white shadow-sm transition-transform duration-200">
        </span>
    </button>

    @if($label)
        <span class="text-sm font-medium leading-5 text-zinc-700">{{ $label }}</span>
    @endif
</div>

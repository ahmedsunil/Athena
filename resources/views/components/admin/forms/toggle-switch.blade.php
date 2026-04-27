{{--
    Forms: ToggleSwitch
    -------------------
    On/off toggle switch. Binds to a Livewire boolean property.

    Usage:
        <x-admin.forms.toggle-switch wire:model="isPublished" label="Published" />

        {{-- No label (standalone) --}}
        <x-admin.forms.toggle-switch wire:model="isActive" />

    Props:
        $label    (optional) — text label displayed next to the toggle
        $modelValue is read via $attributes — wire:model handles the state

    Implementation note:
        Uses Alpine.js to reflect the Livewire boolean as a local `on` value.
        The track color and knob position animate based on `on`.
--}}
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
        :class="on ? 'bg-teal-500' : 'bg-stone-200'"
        class="inline-flex h-5 w-9 shrink-0 items-center rounded-full transition-colors duration-200 focus:outline-none">
        {{-- Knob --}}
        <span
            :class="on ? 'translate-x-4' : 'translate-x-0.5'"
            class="inline-block h-3.5 w-3.5 rounded-full bg-white shadow transition-transform duration-200">
        </span>
    </button>

    @if($label)
        <span class="text-sm font-medium text-stone-700">{{ $label }}</span>
    @endif
</div>

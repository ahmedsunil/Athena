{{--
    Forms: Checkbox
    ---------------
    Labeled checkbox with optional description line.

    Usage:
        {{-- Simple --}}
        <x-admin.forms.checkbox wire:model="isActive" label="Active" />

        {{-- With description --}}
        <x-admin.forms.checkbox
            wire:model="isVisibleOnWebsite"
            label="Show on website"
            description="Visible in the public product catalog"
        />

    Props:
        $label        (required) — checkbox label text
        $description  (optional) — small muted text below label
        All other attributes (wire:model, value, etc.) forwarded automatically.
--}}
@props(['label', 'description' => null])

<label class="flex cursor-pointer items-start gap-3">
    <input
        type="checkbox"
        {{ $attributes->class(['mt-0.5 h-4 w-4 rounded border-stone-300 text-teal-600 focus:ring-teal-500']) }}
    >
    <div>
        <p class="text-sm font-medium text-stone-700">{{ $label }}</p>
        @if($description)
            <p class="text-xs text-stone-400">{{ $description }}</p>
        @endif
    </div>
</label>

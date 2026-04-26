{{--
    Forms: FormField
    ----------------
    Wrapper that renders a label, an input slot, and an @error message.
    Use this to wrap any individual form control for consistent spacing.

    Usage:
        <x-admin.forms.form-field label="Customer Name" field="customerName">
            <input type="text"
                   wire:model="customerName"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
        </x-admin.forms.form-field>

        {{-- Required indicator --}}
        <x-admin.forms.form-field label="Email" field="email" required>
            ...
        </x-admin.forms.form-field>

        {{-- Hint text --}}
        <x-admin.forms.form-field label="Description" field="description" hint="Max 500 characters">
            ...
        </x-admin.forms.form-field>

    Props:
        $label     (required) — field label text
        $field     (required) — matches the wire:model / @error key
        $required  (optional) — adds * to the label
        $hint      (optional) — small help text below the input
--}}
@props(['label', 'field', 'required' => false, 'hint' => null])

<div>
    <label class="mb-1.5 block text-xs font-medium text-stone-700">
        {{ $label }}{{ $required ? ' *' : '' }}
    </label>

    {{ $slot }}

    @error($field)
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror

    @if($hint && !$errors->has($field))
        <p class="mt-1 text-xs text-stone-400">{{ $hint }}</p>
    @endif
</div>

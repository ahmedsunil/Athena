{{--
    Forms: FormField
    ----------------
    Wrapper that renders a label, an input slot, and an @error message.
    Use this to wrap any individual form control for consistent spacing.
    Props: label, field, required, hint.
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

{{--
    Forms: FormField
    ----------------
    Wrapper that renders a label, an input slot, and an @error message.
    Use this to wrap any individual form control for consistent spacing.
    Props: label, field, required, hint.
--}}
@props(['label', 'field', 'required' => false, 'hint' => null])

<div>
    <label class="admin-label mb-1.5 block">
        {{ $label }}{{ $required ? ' *' : '' }}
    </label>

    {{ $slot }}

    @error($field)
    <p class="mt-1 admin-form-error">{{ $message }}</p>
    @enderror

    @if($hint && !$errors->has($field))
        <p class="admin-muted mt-1">{{ $hint }}</p>
    @endif
</div>

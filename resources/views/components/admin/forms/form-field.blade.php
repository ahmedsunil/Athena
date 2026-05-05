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

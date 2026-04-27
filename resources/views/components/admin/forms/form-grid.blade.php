{{--
    Forms: FormGrid
    ---------------
    Responsive grid wrapper for form fields. Defaults to 2 columns.

    Usage:
        {{-- 2-column grid (default) --}}
        <x-admin.forms.form-grid>
            <x-admin.forms.form-field label="First Name" field="firstName" required>
                <x-admin.forms.text-input wire:model="firstName" />
            </x-admin.forms.form-field>
            <x-admin.forms.form-field label="Last Name" field="lastName">
                <x-admin.forms.text-input wire:model="lastName" />
            </x-admin.forms.form-field>
        </x-admin.forms.form-grid>

        {{-- 3-column grid --}}
        <x-admin.forms.form-grid cols="3">
            ...
        </x-admin.forms.form-grid>

        {{-- Single column (no grid) --}}
        <x-admin.forms.form-grid cols="1">
            ...
        </x-admin.forms.form-grid>

    Props:
        $cols  (optional) — number of columns: 1 | 2 | 3. Default: 2
--}}
@props(['cols' => 2])

@php
    $gridClass = match((int)$cols) {
        1       => 'grid grid-cols-1 gap-4',
        3       => 'grid grid-cols-1 gap-4 sm:grid-cols-3',
        default => 'grid grid-cols-2 gap-4',
    };
@endphp

<div class="{{ $gridClass }}">
    {{ $slot }}
</div>

{{--
    Forms: Textarea
    ---------------
    Multi-line text area with consistent styling.

    Usage:
        <x-admin.forms.textarea wire:model="description" rows="3" placeholder="Optional..." />

        {{-- Inside a form-field wrapper --}}
        <x-admin.forms.form-field label="Notes" field="notes">
            <x-admin.forms.textarea wire:model="notes" rows="4" />
        </x-admin.forms.form-field>

    Props:
        $rows         (optional) — number of rows. Default: 3
        $placeholder  (optional) — placeholder text
        All other attributes forwarded automatically.
--}}
@props(['rows' => 3, 'placeholder' => ''])

<textarea
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->class(['w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500']) }}
>{{ $slot }}</textarea>

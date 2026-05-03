{{--
    Forms: Textarea
    ---------------
    Multi-line text area with consistent styling.
--}}
@props(['rows' => 3, 'placeholder' => ''])

<textarea
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->class(['admin-form-control w-full rounded-lg border border-zinc-200 bg-white px-3 py-2 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
>{{ $slot }}</textarea>

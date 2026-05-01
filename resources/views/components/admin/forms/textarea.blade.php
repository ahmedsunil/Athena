{{--
    Forms: Textarea
    ---------------
    Multi-line text area with consistent styling.
--}}
@props(['rows' => 3, 'placeholder' => ''])

<textarea
    rows="{{ $rows }}"
    placeholder="{{ $placeholder }}"
    {{ $attributes->class(['w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950']) }}
>{{ $slot }}</textarea>

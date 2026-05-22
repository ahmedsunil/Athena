@props(['label' => null])

<label class="flex cursor-pointer items-center gap-2.5">
    <input type="checkbox" {{ $attributes->whereStartsWith('wire:model') }} class="peer sr-only">

    <span class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full bg-zinc-200 shadow-sm transition-colors duration-200 after:absolute after:left-0.5 after:h-3.5 after:w-3.5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform after:duration-200 peer-checked:bg-zinc-900 peer-checked:after:translate-x-4 peer-focus:ring-2 peer-focus:ring-zinc-950/10"></span>

    @if($label)
        <span class="text-sm font-medium leading-5 text-zinc-700">{{ $label }}</span>
    @endif
</label>

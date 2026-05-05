@props(['label', 'description' => null])

<label class="flex cursor-pointer items-start gap-3">
    <input
        type="checkbox"
        {{ $attributes->class(['mt-0.5 h-4 w-4 rounded border-zinc-300 text-zinc-950 shadow-sm focus:ring-zinc-950']) }}
    >
    <div>
        <p class="text-sm font-medium leading-5 text-zinc-700">{{ $label }}</p>
        @if($description)
            <p class="admin-muted">{{ $description }}</p>
        @endif
    </div>
</label>

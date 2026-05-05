@props(['viewHref' => null, 'editHref' => null, 'deleteAction' => null])

<div class="flex items-center justify-end gap-2">

    @if($viewHref)
        <a href="{{ $viewHref }}"
           class="admin-label hover:text-zinc-950">View</a>
    @endif

    @if($editHref)
        <a href="{{ $editHref }}"
           class="admin-label-muted hover:text-zinc-700">Edit</a>
    @endif

    @if($deleteAction)
        <button wire:click="{{ $deleteAction }}"
                class="admin-link-label text-red-400 hover:text-red-600">Delete</button>
    @endif

    {{-- Custom actions slot --}}
    {{ $slot }}

</div>

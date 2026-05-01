{{--
    Tables: RowActions
    ------------------
    Inline action link row for table rows (Edit, View, Delete, etc.).
    Renders a right-aligned flex group of text links.

    Usage:
        {{-- Default edit + delete --}}
        <x-admin.tables.row-actions
            edit-href="{{ route('orders.edit', $item) }}"
            delete-action="confirmDelete({{ $item->id }}, 'Order #{{ $item->id }}')"
        />

        {{-- Add a view link --}}
        <x-admin.tables.row-actions
            view-href="{{ route('orders.show', $item) }}"
            edit-href="{{ route('orders.edit', $item) }}"
            delete-action="confirmDelete({{ $item->id }}, 'Order #{{ $item->id }}')"
        />

        {{-- Custom actions in slot --}}
        <x-admin.tables.row-actions>
            <a href="{{ route('orders.pdf', $item) }}" target="_blank"
               class="admin-label hover:text-zinc-950">PDF</a>
            <button wire:click="confirmDelete({{ $item->id }}, 'Order #{{ $item->id }}')"
                    class="admin-link-label text-red-400 hover:text-red-600">Delete</button>
        </x-admin.tables.row-actions>

    Props:
        $viewHref      (optional) — URL for a "View" link
        $editHref      (optional) — URL for an "Edit" link
        $deleteAction  (optional) — Livewire expression for a "Delete" button
--}}
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

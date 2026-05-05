@props([
    'count'       => 0,
    'label'       => 'item',
    'action'      => 'bulkDelete',
    'actionLabel' => 'Delete Selected',
])

<div class="mb-3 flex items-center justify-between rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 shadow-sm">
    <span class="text-sm font-medium leading-5 text-red-700">
        {{ $count }} {{ Str::plural($label, $count) }} selected
    </span>
    <button wire:click="{{ $action }}"
            wire:confirm="Delete {{ $count }} selected {{ Str::plural($label, $count) }}? This cannot be undone."
            class="rounded-lg bg-red-600 px-3 py-1.5 admin-button-label text-white shadow-sm transition-colors hover:bg-red-700">
        {{ $actionLabel }}
    </button>
</div>

<div class="space-y-4">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="admin-page-title">Links</h1>
            <p class="admin-muted">Manage named links used across the site.</p>
        </div>
        <a href="{{ route('cms.links.create') }}"
           class="rounded-lg bg-zinc-950 px-3 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-800">
            + New link
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
        <table class="admin-table">
            <thead>
                <tr class="border-b border-zinc-100 text-left">
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Name</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Route</th>
                    <th class="px-4 py-3 admin-link-label text-zinc-500">Actions</th>
                </tr>
            </thead>
            <tbody class="admin-table-body">
                @forelse($links as $link)
                    <tr>
                        <td class="admin-table-cell-primary">{{ $link->name }}</td>
                        <td class="px-4 py-3 font-mono text-sm text-zinc-500">{{ $link->route }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('cms.links.edit', $link) }}"
                                   class="admin-link-label text-zinc-950 hover:text-zinc-700">Edit</a>
                                <button wire:click="confirmDelete({{ $link->id }})"
                                        class="admin-link-label text-red-500 hover:text-red-700">Delete</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-12 text-center admin-body-muted">No links yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-xl">
                <h3 class="admin-section-title">Delete link?</h3>
                <p class="mt-1 admin-caption">This action cannot be undone.</p>
                <div class="mt-4 flex gap-2">
                    <button wire:click="delete"
                            class="flex-1 rounded-lg bg-red-600 py-2 admin-button-label text-white hover:bg-red-700">
                        Delete
                    </button>
                    <button wire:click="$set('confirmingDelete', false)"
                            class="flex-1 rounded-lg border border-zinc-300 py-2 admin-label hover:bg-zinc-50">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

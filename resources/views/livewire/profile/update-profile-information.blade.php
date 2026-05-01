<div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h3 class="admin-section-title">Profile Information</h3>
        <p class="mt-1 admin-caption">Update your name and email address.</p>
    </div>

    <form wire:submit="updateProfileInformation" class="space-y-4">

        <div>
            <label class="mb-1.5 block admin-label">Name</label>
            <input type="text" wire:model="name" autocomplete="name"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('name') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Email address</label>
            <input type="email" wire:model="email" autocomplete="email"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed"
                    class="inline-flex items-center gap-2 rounded-lg bg-zinc-950 px-4 py-2 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800 disabled:opacity-60">
                <svg wire:loading wire:target="updateProfileInformation" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Save changes
            </button>
        </div>

    </form>
</div>

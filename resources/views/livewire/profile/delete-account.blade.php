<div class="rounded-xl border border-red-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-red-700">Delete Account</h3>
        <p class="mt-1 text-xs text-zinc-500">Permanently delete your account and all associated data. This cannot be undone.</p>
    </div>

    @if(! $confirming)
        <button wire:click="confirmDeletion"
                class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-700">
            Delete account
        </button>
    @else
        <div class="space-y-3">
            <p class="text-xs text-zinc-600">Enter your password to confirm account deletion. This action is irreversible.</p>
            <div>
                <input type="password" wire:model="password" placeholder="Password"
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-red-400 focus:outline-none focus:ring-1 focus:ring-red-400">
                @if($error)
                    <p class="mt-1 text-xs text-red-600">{{ $error }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <button wire:click="deleteAccount"
                        class="rounded-lg bg-red-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-red-700">
                    Yes, delete my account
                </button>
                <button wire:click="$set('confirming', false)"
                        class="rounded-lg border border-zinc-300 px-4 py-2 text-xs font-medium text-zinc-700 hover:bg-zinc-50">
                    Cancel
                </button>
            </div>
        </div>
    @endif
</div>

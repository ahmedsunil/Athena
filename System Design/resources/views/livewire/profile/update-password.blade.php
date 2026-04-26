<div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h3 class="text-sm font-semibold text-zinc-950">Update Password</h3>
        <p class="mt-1 text-xs text-zinc-500">Use a long, random password to stay secure.</p>
    </div>

    <form wire:submit="updatePassword" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-zinc-700">Current password</label>
            <input type="password" wire:model="currentPassword" autocomplete="current-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('currentPassword') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-zinc-700">New password</label>
            <input type="password" wire:model="password" autocomplete="new-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-zinc-700">Confirm new password</label>
            <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('passwordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="rounded-lg bg-zinc-950 px-4 py-2 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800">
                Update password
            </button>

            @if($status === 'saved')
                <span class="text-xs text-zinc-950" x-data x-init="setTimeout(() => $el.remove(), 2500)">Saved.</span>
            @endif
        </div>

    </form>
</div>

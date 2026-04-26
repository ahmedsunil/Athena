<div>
    <h2 class="mb-2 text-base font-semibold text-zinc-950">Confirm password</h2>
    <p class="mb-6 text-sm text-zinc-500">For your security, please confirm your password to continue.</p>

    <form wire:submit="confirmPassword" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-zinc-700">Password</label>
            <input type="password" wire:model="password" autocomplete="current-password" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800">
            Confirm
        </button>

    </form>
</div>

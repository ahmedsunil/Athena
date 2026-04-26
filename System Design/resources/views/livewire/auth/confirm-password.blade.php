<div>
    <h2 class="mb-2 text-base font-semibold text-stone-900">Confirm password</h2>
    <p class="mb-6 text-sm text-stone-500">For your security, please confirm your password to continue.</p>

    <form wire:submit="confirmPassword" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Password</label>
            <input type="password" wire:model="password" autocomplete="current-password" autofocus
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            Confirm
        </button>

    </form>
</div>

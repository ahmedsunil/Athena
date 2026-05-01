<div>
    <h2 class="mb-6 admin-page-title">Set new password</h2>

    <form wire:submit="resetPassword" class="space-y-4">

        <div>
            <label class="mb-1.5 block admin-label">Email address</label>
            <input type="email" wire:model="email" autocomplete="email"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">New password</label>
            <input type="password" wire:model="password" autocomplete="new-password" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('password') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Confirm new password</label>
            <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('passwordConfirmation') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Reset password
        </button>

    </form>
</div>

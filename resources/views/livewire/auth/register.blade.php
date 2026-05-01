<div>
    <h2 class="mb-6 admin-page-title">Create an account</h2>

    <form wire:submit="register" class="space-y-4">

        <div>
            <label class="mb-1.5 block admin-label">Full name</label>
            <input type="text" wire:model="name" autocomplete="name" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('name') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Email address</label>
            <input type="email" wire:model="email" autocomplete="email"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Password</label>
            <input type="password" wire:model="password" autocomplete="new-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('password') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block admin-label">Confirm password</label>
            <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('passwordConfirmation') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Create account
        </button>

    </form>

    <p class="mt-6 text-center admin-muted">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-zinc-950 hover:text-zinc-800">Sign in</a>
    </p>
</div>

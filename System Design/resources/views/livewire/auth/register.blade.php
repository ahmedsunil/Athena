<div>
    <h2 class="mb-6 text-base font-semibold text-stone-900">Create an account</h2>

    <form wire:submit="register" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Full name</label>
            <input type="text" wire:model="name" autocomplete="name" autofocus
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
            <input type="email" wire:model="email" autocomplete="email"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Password</label>
            <input type="password" wire:model="password" autocomplete="new-password"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Confirm password</label>
            <input type="password" wire:model="passwordConfirmation" autocomplete="new-password"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('passwordConfirmation') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            Create account
        </button>

    </form>

    <p class="mt-6 text-center text-xs text-stone-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-teal-600 hover:text-teal-700">Sign in</a>
    </p>
</div>

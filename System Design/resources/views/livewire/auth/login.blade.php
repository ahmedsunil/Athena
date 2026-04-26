<div>
    <h2 class="mb-6 text-base font-semibold text-stone-900">Sign in to your account</h2>

    <form wire:submit="login" class="space-y-4">

        {{-- Email --}}
        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
            <input type="email" wire:model="email" autocomplete="email" autofocus
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Password</label>
            <input type="password" wire:model="password" autocomplete="current-password"
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-xs text-stone-600">
                <input type="checkbox" wire:model="remember"
                       class="h-3.5 w-3.5 rounded border-stone-300 text-teal-600 focus:ring-teal-500">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700">
                Forgot password?
            </a>
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            Sign in
        </button>

    </form>

    <p class="mt-6 text-center text-xs text-stone-400">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-teal-600 hover:text-teal-700">Register</a>
    </p>
</div>

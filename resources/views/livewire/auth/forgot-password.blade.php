<div>
    <h2 class="mb-2 text-base font-semibold text-zinc-950">Reset your password</h2>
    <p class="mb-6 text-sm text-zinc-500">Enter your email and we'll send you a reset link.</p>

    @if($status)
        <div class="mb-4 rounded-lg bg-zinc-100 px-4 py-3 text-sm text-zinc-800">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-zinc-700">Email address</label>
            <input type="email" wire:model="email" autocomplete="email" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800">
            Send reset link
        </button>

    </form>

    <p class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-xs font-medium text-zinc-950 hover:text-zinc-800">← Back to sign in</a>
    </p>
</div>

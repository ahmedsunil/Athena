<div>
    <h2 class="mb-2 text-base font-semibold text-stone-900">Reset your password</h2>
    <p class="mb-6 text-sm text-stone-500">Enter your email and we'll send you a reset link.</p>

    @if($status)
        <div class="mb-4 rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-700">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-4">

        <div>
            <label class="mb-1.5 block text-xs font-medium text-stone-700">Email address</label>
            <input type="email" wire:model="email" autocomplete="email" autofocus
                   class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 placeholder-stone-400 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
            @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            Send reset link
        </button>

    </form>

    <p class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-xs font-medium text-teal-600 hover:text-teal-700">← Back to sign in</a>
    </p>
</div>

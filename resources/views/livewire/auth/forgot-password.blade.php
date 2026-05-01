<div>
    <h2 class="mb-2 admin-page-title">Reset your password</h2>
    <p class="mb-6 admin-body-muted">Enter your email and we'll send you a reset link.</p>

    @if($status)
        <div class="mb-4 rounded-lg bg-zinc-100 px-4 py-3 text-sm text-zinc-800">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="sendResetLink" class="space-y-4">

        <div>
            <label class="mb-1.5 block admin-label">Email address</label>
            <input type="email" wire:model="email" autocomplete="email" autofocus
                   class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-700 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
            @error('email') <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Send reset link
        </button>

    </form>

    <p class="mt-6 text-center">
        <a href="{{ route('login') }}" class="admin-link-label text-zinc-950 hover:text-zinc-800">← Back to sign in</a>
    </p>
</div>

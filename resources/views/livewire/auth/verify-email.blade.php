<div>
    <h2 class="mb-2 text-base font-semibold text-zinc-950">Verify your email</h2>
    <p class="mb-6 text-sm text-zinc-500">
        Please verify your email by clicking the link we sent to your address.
    </p>

    @if($status)
        <div class="mb-4 rounded-lg bg-zinc-100 px-4 py-3 text-sm text-zinc-800">
            {{ $status }}
        </div>
    @endif

    <button wire:click="sendVerification"
            class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800">
        Resend verification email
    </button>

    <button wire:click="logout" class="mt-4 w-full text-center text-xs font-medium text-zinc-500 hover:text-zinc-700">
        Log out
    </button>
</div>

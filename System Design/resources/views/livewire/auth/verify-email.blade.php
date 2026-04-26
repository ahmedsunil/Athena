<div>
    <h2 class="mb-2 text-base font-semibold text-stone-900">Verify your email</h2>
    <p class="mb-6 text-sm text-stone-500">
        Please verify your email by clicking the link we sent to your address.
    </p>

    @if($status)
        <div class="mb-4 rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-700">
            {{ $status }}
        </div>
    @endif

    <button wire:click="sendVerification"
            class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
        Resend verification email
    </button>

    <button wire:click="logout" class="mt-4 w-full text-center text-xs font-medium text-stone-500 hover:text-stone-700">
        Log out
    </button>
</div>

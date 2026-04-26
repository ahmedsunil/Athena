<div>
    <h2 class="mb-2 text-base font-semibold text-stone-900">Two-factor authentication</h2>
    <p class="mb-6 text-sm text-stone-500">
        @if($useRecovery)
            Enter one of your emergency recovery codes.
        @else
            Enter the code from your authenticator app.
        @endif
    </p>

    <div class="space-y-4">

        @if(! $useRecovery)
            <div>
                <label class="mb-1.5 block text-xs font-medium text-stone-700">Authentication code</label>
                <input type="text" wire:model="code"
                       inputmode="numeric" autocomplete="one-time-code" autofocus
                       maxlength="6" placeholder="000000"
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 text-center text-lg font-mono tracking-widest text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        @else
            <div>
                <label class="mb-1.5 block text-xs font-medium text-stone-700">Recovery code</label>
                <input type="text" wire:model="recoveryCode"
                       autocomplete="one-time-code" autofocus
                       class="w-full rounded-lg border border-stone-300 px-3 py-2 font-mono text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                @error('recoveryCode') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif

        <button wire:click="authenticate"
                class="w-full rounded-lg bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
            Verify
        </button>

        <button type="button" wire:click="$set('useRecovery', {{ $useRecovery ? 'false' : 'true' }})"
                class="w-full text-center text-xs font-medium text-teal-600 hover:text-teal-700">
            {{ $useRecovery ? 'Use authentication code' : 'Use recovery code' }}
        </button>

    </div>
</div>

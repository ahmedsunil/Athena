<div>
    <h2 class="mb-2 admin-page-title">Two-factor authentication</h2>
    <p class="mb-6 admin-body-muted">
        @if($useRecovery)
            Enter one of your emergency recovery codes.
        @else
            Enter the code from your authenticator app.
        @endif
    </p>

    <div class="space-y-4">

        @if(! $useRecovery)
            <div>
                <label class="mb-1.5 block admin-label">Authentication code</label>
                <input type="text" wire:model.live="code"
                       inputmode="numeric" autocomplete="one-time-code" autofocus
                       maxlength="6" placeholder="000000"
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-center text-lg font-mono tracking-widest text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('code') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
        @else
            <div>
                <label class="mb-1.5 block admin-label">Recovery code</label>
                <input type="text" wire:model="recoveryCode"
                       autocomplete="one-time-code" autofocus
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 font-mono text-sm font-normal leading-5 text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('recoveryCode') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>
        @endif

        <button wire:click="authenticate"
                class="w-full rounded-lg bg-zinc-950 px-4 py-2.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Verify
        </button>

        <button type="button" wire:click="$set('useRecovery', {{ $useRecovery ? 'false' : 'true' }})"
                class="w-full text-center admin-link-label text-zinc-950 hover:text-zinc-800">
            {{ $useRecovery ? 'Use authentication code' : 'Use recovery code' }}
        </button>

    </div>
</div>

<div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6 flex items-start justify-between gap-4">
        <div>
            <h3 class="admin-section-title">Two-Factor Authentication</h3>
            <p class="mt-1 admin-caption">Add extra security with an authenticator app.</p>
        </div>
        @if($enabled)
            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2.5 py-0.5 text-xs font-medium text-zinc-800">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                Enabled
            </span>
        @else
            <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2.5 py-0.5 admin-link-label text-zinc-500">
                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                Disabled
            </span>
        @endif
    </div>

    @if($enabled)

        @if($showingRecoveryCodes && count($recoveryCodes))
            <div class="mb-4 rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                <p class="mb-2 admin-label">Store these recovery codes in a safe place.</p>
                <div class="grid grid-cols-2 gap-1">
                    @foreach($recoveryCodes as $code)
                        <code class="text-xs font-mono text-zinc-600">{{ $code }}</code>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            @if(! $showingRecoveryCodes)
                <button wire:click="showRecoveryCodes"
                        class="rounded-lg border border-zinc-300 px-3 py-1.5 admin-label hover:bg-zinc-50">
                    Show recovery codes
                </button>
            @endif
            <button wire:click="regenerateRecoveryCodes"
                    class="rounded-lg border border-zinc-300 px-3 py-1.5 admin-label hover:bg-zinc-50">
                Regenerate codes
            </button>
            <button wire:click="disableTwoFactor"
                    class="rounded-lg bg-red-600 px-3 py-1.5 admin-link-label text-white hover:bg-red-700">
                Disable 2FA
            </button>
        </div>

    @elseif($showingConfirmation)

        @if($qrCodeSvg)
            <div class="mb-4 flex justify-center">
                <div class="rounded-xl bg-white p-2 shadow-sm ring-1 ring-zinc-200">
                    {!! $qrCodeSvg !!}
                </div>
            </div>
            @if($setupKey)
                <p class="mb-4 text-center admin-caption">
                    Setup key: <code class="font-mono">{{ $setupKey }}</code>
                </p>
            @endif
        @endif

        <div class="space-y-3">
            <div>
                <label class="mb-1.5 block admin-label">Confirmation code</label>
                <input type="text" wire:model.live="code"
                       inputmode="numeric" autocomplete="one-time-code" autofocus
                       maxlength="6" placeholder="000000"
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-center text-lg font-mono tracking-widest text-zinc-700 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @if($error)
                    <p class="mt-1 text-xs font-normal leading-5 text-red-600">{{ $error }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <button wire:click="confirmTwoFactor"
                        wire:loading.attr="disabled" wire:target="confirmTwoFactor"
                        class="inline-flex items-center gap-2 rounded-lg bg-zinc-950 px-4 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60">
                    <svg wire:loading wire:target="confirmTwoFactor" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    Confirm & enable
                </button>
                <button wire:click="disableTwoFactor"
                        class="rounded-lg border border-zinc-300 px-4 py-2 admin-label hover:bg-zinc-50">
                    Cancel
                </button>
            </div>
        </div>

    @else

        <p class="mb-4 admin-caption">
            When enabled, you'll be prompted for a code from your authenticator app during login.
        </p>
        <button wire:click="enableTwoFactor"
                wire:loading.attr="disabled" wire:target="enableTwoFactor"
                class="inline-flex items-center gap-2 rounded-lg bg-zinc-950 px-4 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60">
            <svg wire:loading wire:target="enableTwoFactor" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            Enable 2FA
        </button>

    @endif
</div>

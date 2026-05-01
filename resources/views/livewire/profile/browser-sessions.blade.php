<div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm">
    <div class="mb-6">
        <h3 class="admin-section-title">Browser Sessions</h3>
        <p class="mt-1 admin-caption">Manage and log out of your active sessions on other browsers and devices.</p>
    </div>

    @if(count($sessions))
        <div class="mb-6 divide-y divide-zinc-100">
            @foreach($sessions as $session)
                <div class="flex items-center gap-3 py-3">
                    <div class="flex-shrink-0 text-zinc-400">
                        @if($session->agent->isDesktop)
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0H3"/>
                            </svg>
                        @else
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18h3"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="admin-label">
                            {{ $session->agent->platform ?? 'Unknown' }} — {{ $session->agent->browser ?? 'Unknown' }}
                        </p>
                        <p class="admin-muted">
                            {{ $session->ipAddress }}
                            @if($session->isCurrentDevice)
                                · <span class="text-zinc-950">This device</span>
                            @else
                                · Last active {{ $session->lastActive }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(! $confirmingLogout)
        <button wire:click="confirmLogoutOtherBrowserSessions"
                class="rounded-lg bg-zinc-800 px-4 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-950">
            Log out other sessions
        </button>
    @else
        <div class="space-y-3">
            <p class="text-xs font-normal leading-5 text-zinc-600">Enter your password to confirm you want to log out of all other sessions.</p>
            <div>
                <input type="password" wire:model="password" placeholder="Password"
                       class="w-full rounded-lg border border-zinc-300 px-3 py-2 text-sm font-normal leading-5 text-zinc-950 placeholder-zinc-400 focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @if($error)
                    <p class="mt-1 admin-form-error">{{ $error }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <button wire:click="logoutOtherBrowserSessions"
                        class="rounded-lg bg-zinc-800 px-4 py-2 admin-button-label text-white shadow-sm hover:bg-zinc-950">
                    Confirm
                </button>
                <button wire:click="$set('confirmingLogout', false)"
                        class="rounded-lg border border-zinc-300 px-4 py-2 admin-label hover:bg-zinc-50">
                    Cancel
                </button>
            </div>
        </div>
    @endif
</div>

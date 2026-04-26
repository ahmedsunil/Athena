<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h1 class="text-base font-semibold text-zinc-950">System Settings</h1>
        <p class="text-xs text-zinc-500">Configure application-wide features and integrations.</p>
    </div>

    {{-- Google Login --}}
    <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
        <div class="flex items-start justify-between gap-4 border-b border-zinc-100 p-5">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-white shadow-sm">
                    <svg viewBox="0 0 24 24" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-zinc-950">Google Login</p>
                    <p class="text-xs text-zinc-500">Allow users to sign in with their Google account.</p>
                </div>
            </div>

            {{-- Toggle --}}
            <button type="button"
                    wire:click="$toggle('googleLoginEnabled')"
                    class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-zinc-950 focus:ring-offset-2 {{ $googleLoginEnabled ? 'bg-zinc-950' : 'bg-zinc-200' }}">
                <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow transition-transform duration-200 {{ $googleLoginEnabled ? 'translate-x-4' : 'translate-x-0' }}"></span>
            </button>
        </div>

        <div class="p-5 space-y-4"
             x-data="{ enabled: @entangle('googleLoginEnabled') }"
             x-show="enabled"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Client ID</label>
                    <input type="text" wire:model="googleClientId"
                           placeholder="xxxxxxxxxxxx.apps.googleusercontent.com"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('googleClientId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-medium text-zinc-700">Client Secret</label>
                    <input type="password" wire:model="googleClientSecret"
                           placeholder="GOCSPX-…"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('googleClientSecret') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block text-xs font-medium text-zinc-700">Redirect URI</label>
                <input type="url" wire:model="googleRedirectUri"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                <p class="mt-1 text-xs text-zinc-400">Copy this URL into your Google Cloud Console → Authorised redirect URIs.</p>
                @error('googleRedirectUri') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-lg bg-zinc-50 p-3 text-xs text-zinc-500 space-y-1 border border-zinc-200">
                <p class="font-medium text-zinc-700">Setup guide</p>
                <ol class="list-decimal list-inside space-y-0.5">
                    <li>Go to <span class="font-medium text-zinc-950">console.cloud.google.com</span> → APIs &amp; Services → Credentials.</li>
                    <li>Create an <span class="font-medium">OAuth 2.0 Client ID</span> (Web application).</li>
                    <li>Add the Redirect URI above to <span class="font-medium">Authorised redirect URIs</span>.</li>
                    <li>Copy the Client ID and Client Secret here, then save.</li>
                </ol>
            </div>
        </div>

        <div class="flex justify-end border-t border-zinc-100 px-5 py-3">
            <button wire:click="saveGoogle"
                    wire:loading.attr="disabled" wire:loading.class="opacity-60 cursor-not-allowed"
                    wire:target="saveGoogle"
                    class="inline-flex h-8 items-center gap-2 rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-zinc-800 disabled:opacity-60">
                <svg wire:loading wire:target="saveGoogle" class="h-3 w-3 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                Save
            </button>
        </div>
    </div>

</div>

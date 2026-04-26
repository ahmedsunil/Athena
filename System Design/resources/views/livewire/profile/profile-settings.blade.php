<div class="space-y-6 px-4 py-5 sm:px-6" x-data="{ tab: 'profile' }">
    <div>
        <h1 class="text-base font-semibold text-zinc-950">Settings</h1>
        <p class="mt-1 text-xs text-zinc-500">Manage your profile and account settings.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
        <aside class="space-y-1">
            <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-zinc-400">Profile Settings</p>
            <button type="button"
                    @click="tab = 'profile'"
                    :class="tab === 'profile' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950'"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors">
                Profile
            </button>
            <button type="button"
                    @click="tab = 'password'"
                    :class="tab === 'password' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950'"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors">
                Password
            </button>
            <button type="button"
                    @click="tab = 'two-factor'"
                    :class="tab === 'two-factor' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950'"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors">
                Two-Factor Auth
            </button>
            <button type="button"
                    @click="tab = 'appearance'"
                    :class="tab === 'appearance' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950'"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors">
                Appearance
            </button>
        </aside>

        <div class="min-w-0 space-y-4">
            <section x-show="tab === 'profile'" x-cloak class="space-y-4">
                <livewire:profile.update-profile-information />
                <livewire:profile.delete-account />
            </section>

            <section x-show="tab === 'password'" x-cloak>
                <livewire:profile.update-password />
            </section>

            <section x-show="tab === 'two-factor'" x-cloak>
                <livewire:profile.two-factor-authentication />
            </section>

            <section x-show="tab === 'appearance'" x-cloak>
                <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
                    <div class="mb-5">
                        <h2 class="text-sm font-semibold text-zinc-950">Appearance</h2>
                        <p class="mt-1 text-xs text-zinc-500">Customize how the interface looks on this device.</p>
                    </div>

                    <div class="space-y-3">
                        <label class="flex cursor-pointer items-center justify-between rounded-lg border border-zinc-200 px-3 py-2.5">
                            <span>
                                <span class="block text-sm font-medium text-zinc-950">Light</span>
                                <span class="block text-xs text-zinc-500">Use the default light theme.</span>
                            </span>
                            <input type="radio" name="appearance" checked class="h-4 w-4 border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                        </label>

                        <label class="flex cursor-pointer items-center justify-between rounded-lg border border-zinc-200 px-3 py-2.5">
                            <span>
                                <span class="block text-sm font-medium text-zinc-950">Dark</span>
                                <span class="block text-xs text-zinc-500">Use a darker interface theme.</span>
                            </span>
                            <input type="radio" name="appearance" class="h-4 w-4 border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                        </label>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

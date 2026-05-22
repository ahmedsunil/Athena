<div class="space-y-6 px-4 py-5 sm:px-6">
    <div>
        <h1 class="admin-page-title">Settings</h1>
        <p class="mt-1 admin-caption">Manage your profile and account settings.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[220px_1fr]">
        <aside class="space-y-1">
            <p class="mb-2 px-3 admin-eyebrow">Profile Settings</p>
            <button type="button"
                    wire:click="setTab('profile')"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors {{ $activeTab === 'profile' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' }}">
                Profile
            </button>
            <button type="button"
                    wire:click="setTab('password')"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors {{ $activeTab === 'password' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' }}">
                Password
            </button>
            <button type="button"
                    wire:click="setTab('two-factor')"
                    class="flex w-full items-center rounded-lg px-3 py-2 text-left text-sm font-medium transition-colors {{ $activeTab === 'two-factor' ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' }}">
                Two-Factor Auth
            </button>
        </aside>

        <div class="min-w-0 space-y-4">
            @if($activeTab === 'profile')
            <section class="space-y-4">
                <livewire:profile.update-profile-information />
                <livewire:profile.delete-account />
            </section>
            @endif

            @if($activeTab === 'password')
            <section>
                <livewire:profile.update-password />
            </section>
            @endif

            @if($activeTab === 'two-factor')
            <section>
                <livewire:profile.two-factor-authentication />
            </section>
            @endif

        </div>
    </div>
</div>

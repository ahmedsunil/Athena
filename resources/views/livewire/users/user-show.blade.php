<div class="mx-auto max-w-4xl space-y-4">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" wire:navigate
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <div>
                <h1 class="admin-page-title">View user</h1>
                <p class="admin-caption">Read-only account details and access status.</p>
            </div>
        </div>

        <a href="{{ route('users.edit', $user->id) }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Edit user
        </a>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="admin-eyebrow">User #{{ $user->id }}</p>
                <h2 class="mt-1 text-xl font-semibold leading-7 text-zinc-950">{{ $user->name }}</h2>
                <p class="admin-muted">{{ $user->email }}</p>
            </div>
            <span class="inline-flex w-fit rounded-full px-2 py-0.5 admin-link-label {{ $user->is_active ? 'bg-zinc-100 text-zinc-950' : 'bg-red-50 text-red-700' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <h3 class="admin-section-title">Account</h3>
            <dl class="mt-4 space-y-3">
                <div>
                    <dt class="admin-label-muted">Email verified</dt>
                    <dd class="mt-0.5 text-sm text-zinc-950">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y g:i A') : 'No' }}</dd>
                </div>
                <div>
                    <dt class="admin-label-muted">Created</dt>
                    <dd class="mt-0.5 text-sm text-zinc-950">{{ $user->created_at->format('M d, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="admin-label-muted">Updated</dt>
                    <dd class="mt-0.5 text-sm text-zinc-950">{{ $user->updated_at->format('M d, Y g:i A') }}</dd>
                </div>
            </dl>
        </section>

        <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
            <h3 class="admin-section-title">Roles</h3>
            <div class="mt-4 flex flex-wrap gap-1.5">
                @forelse($user->roles->sortBy('name') as $role)
                    <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 admin-badge text-zinc-700">
                        {{ ucfirst($role->name) }}
                    </span>
                @empty
                    <span class="admin-muted">No roles assigned.</span>
                @endforelse
            </div>
        </section>
    </div>
</div>

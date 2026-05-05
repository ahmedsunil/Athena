<div class="mx-auto max-w-4xl space-y-4">
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('roles.index') }}" wire:navigate
               class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <div>
                <h1 class="admin-page-title">View role</h1>
                <p class="admin-caption">Read-only role details and assigned permission groups.</p>
            </div>
        </div>

        <a href="{{ route('roles.edit', $role->id) }}" wire:navigate
           class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            Edit role
        </a>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="admin-eyebrow">Role #{{ $role->id }}</p>
                <h2 class="mt-1 text-xl font-semibold leading-7 text-zinc-950 capitalize">{{ $role->name }}</h2>
                <p class="admin-muted">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</p>
            </div>
        </div>
    </div>

    <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
        <h3 class="admin-section-title">Resources</h3>
        <div class="mt-4 flex flex-wrap gap-1.5">
            @forelse($resources as $resource)
                <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 admin-badge text-zinc-700">
                    {{ $resource }}
                </span>
            @empty
                <span class="admin-muted">No resources assigned.</span>
            @endforelse
        </div>
    </section>

    <section class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm">
        <h3 class="admin-section-title">Permissions</h3>
        <div class="mt-4 space-y-3">
            @forelse($permissions as $group => $groupPermissions)
                <div class="rounded-lg border border-zinc-200 p-4">
                    <p class="admin-eyebrow">{{ $group }}</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        @foreach($groupPermissions as $permission)
                            <span class="inline-flex rounded-full bg-zinc-100 px-2 py-0.5 text-[10px] font-medium text-zinc-600">
                                {{ $permission->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="admin-muted">No permissions assigned.</p>
            @endforelse
        </div>
    </section>
</div>

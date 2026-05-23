<div class="mx-auto max-w-6xl space-y-4">
    <div class="rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm ring-1 ring-zinc-100/70">
        <h1 class="admin-page-title">Dashboard</h1>
        <p class="admin-caption">A quick overview of CMS access.</p>
    </div>

    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-3-soft)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="admin-link-label text-zinc-500">Total users</p>
                    <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-3)">{{ number_format($stats['totalUsers']) }}</p>
                </div>
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/75 text-zinc-500 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-2-soft)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="admin-link-label text-zinc-500">Active users</p>
                    <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-2)">{{ number_format($stats['activeUsers']) }}</p>
                </div>
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/75 text-zinc-500 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-1-soft)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="admin-link-label text-zinc-500">Admins</p>
                    <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-1)">{{ number_format($stats['adminUsers']) }}</p>
                </div>
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/75 text-zinc-500 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-4-soft)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="admin-link-label text-zinc-500">New this month</p>
                    <p class="mt-1 admin-stat-value" style="color: hsl(32 95% 44%)">{{ number_format($stats['newThisMonth']) }}</p>
                </div>
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-white/75 text-zinc-500 shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 2v4M16 2v4M3 10h18"/>
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

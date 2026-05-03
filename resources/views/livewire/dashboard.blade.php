<div class="mx-auto space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-zinc-200 bg-white px-5 py-4 shadow-sm ring-1 ring-zinc-100/70">
        <div>
            <h1 class="admin-page-title">Dashboard</h1>
            <p class="admin-caption">Stats, KPI cards, graphs, and recent users.</p>
        </div>
        <button type="button"
                class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-950 px-3 py-1.5 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/>
            </svg>
            Export
        </button>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-3-soft)">
            <p class="admin-link-label text-zinc-500">Total users</p>
            <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-3)">{{ number_format($stats['totalUsers']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-2-soft)">
            <p class="admin-link-label text-zinc-500">Active users</p>
            <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-2)">{{ number_format($stats['activeUsers']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-1-soft)">
            <p class="admin-link-label text-zinc-500">Admins</p>
            <p class="mt-1 admin-stat-value" style="color: var(--admin-chart-1)">{{ number_format($stats['adminUsers']) }}</p>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm ring-1 ring-zinc-100/70"
             style="background-color: var(--admin-chart-4-soft)">
            <p class="admin-link-label text-zinc-500">New this month</p>
            <p class="mt-1 admin-stat-value" style="color: hsl(32 95% 44%)">{{ number_format($stats['newThisMonth']) }}</p>
        </div>
    </div>

    <div class="grid gap-3 sm:grid-cols-3">
        <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm ring-1 ring-zinc-100/70">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg shadow-sm" style="background-color: var(--admin-chart-2-soft); color: var(--admin-chart-2)">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                    <circle cx="12" cy="12" r="9"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="admin-link-label text-zinc-500">Completed</p>
                <p class="admin-page-title">{{ number_format($stats['activeUsers']) }}</p>
            </div>
            <span class="shrink-0 admin-muted">active</span>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm ring-1 ring-zinc-100/70">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg shadow-sm" style="background-color: var(--admin-chart-4-soft); color: hsl(32 95% 44%)">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 3"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="admin-link-label text-zinc-500">Pending</p>
                <p class="admin-page-title">{{ number_format($pendingUsers) }}</p>
            </div>
            <span class="shrink-0 admin-muted">inactive</span>
        </div>
        <div class="flex items-center gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm ring-1 ring-zinc-100/70">
            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg shadow-sm" style="background-color: var(--admin-chart-3-soft); color: var(--admin-chart-3)">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m4 14v-7m4 7V8m4 11v-4m4 4V9"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="admin-link-label text-zinc-500">Conversion</p>
                <p class="admin-page-title">{{ $conversionRate }}%</p>
            </div>
            <span class="shrink-0 admin-muted">active rate</span>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        <div class="admin-dashboard-card lg:col-span-2">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="admin-section-title">Users by month</h2>
                    <p class="admin-muted">Vertical bar chart</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="usersFrom"
                           class="admin-dashboard-date">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="usersTo"
                           class="admin-dashboard-date">
                </div>
            </div>

            <div class="mb-4 grid grid-cols-3 divide-x divide-zinc-100 rounded-lg bg-zinc-50 px-1 py-2.5">
                <div class="px-3 text-center">
                    <p class="admin-eyebrow">Total</p>
                    <p class="mt-0.5 admin-section-title">{{ number_format($stats['totalUsers']) }}</p>
                    <p class="admin-muted">users</p>
                </div>
                <div class="px-3 text-center">
                    <p class="admin-eyebrow">Active</p>
                    <p class="mt-0.5 admin-section-title">{{ number_format($stats['activeUsers']) }}</p>
                    <p class="admin-muted">verified</p>
                </div>
                <div class="px-3 text-center">
                    <p class="admin-eyebrow">Rate</p>
                    <p class="mt-0.5 admin-section-title">{{ $conversionRate }}%</p>
                    <p class="admin-muted">active</p>
                </div>
            </div>

            <div class="flex h-40 items-end gap-1.5">
                @foreach($usersByMonth as $bar)
                    <div class="group relative flex h-full flex-1 items-end">
                        <div class="w-full rounded-t-sm transition-opacity group-hover:opacity-80"
                             style="height: {{ $bar['percent'] }}%; background-color: var(--admin-chart-3)"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 flex gap-1.5">
                @foreach($usersByMonth as $bar)
                    <div class="flex-1 truncate text-center admin-muted text-[9px]">{{ $bar['label'] }}</div>
                @endforeach
            </div>
        </div>

        <div class="admin-dashboard-card">
            <div class="mb-4 flex flex-col gap-3">
                <h2 class="admin-section-title">Top roles</h2>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="rolesFrom"
                           class="admin-dashboard-date min-w-0">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="rolesTo"
                           class="admin-dashboard-date min-w-0">
                </div>
            </div>
            <div class="space-y-3">
                @foreach($topRoles as $role)
                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="truncate admin-label">{{ $role['label'] }}</span>
                            <span class="admin-muted">{{ number_format($role['total']) }}</span>
                        </div>
                        <div class="h-1.5 overflow-hidden rounded-full bg-zinc-100">
                            <div class="h-full rounded-full" style="width: {{ $role['percent'] }}%; background-color: var(--admin-chart-1)"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-3">
        @php
            $linePoints = collect($lineGraph)->values()->map(function ($point, $index) use ($lineGraph) {
                $count = max(count($lineGraph) - 1, 1);
                $x = round(($index / $count) * 640);
                $y = 180 - round(($point['percent'] / 100) * 150);
                return "{$x},{$y}";
            })->implode(' ');

            $areaPoints = collect($areaGraph)->values()->map(function ($point, $index) use ($areaGraph) {
                $count = max(count($areaGraph) - 1, 1);
                $x = round(($index / $count) * 640);
                $y = 180 - round(($point['percent'] / 100) * 150);
                return "{$x},{$y}";
            })->implode(' ');

            $donutActive = $donutGraph[0]['percent'] ?? 0;
            $donutPending = $donutGraph[1]['percent'] ?? 0;
            $donutInactive = $donutGraph[2]['percent'] ?? 0;
            $donutPendingEnd = $donutActive + $donutPending;
            $donutInactiveEnd = $donutPendingEnd + $donutInactive;
        @endphp

        <div class="admin-dashboard-card lg:col-span-2">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="admin-section-title">Line graph</h2>
                    <p class="admin-muted">Daily user trend</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="lineFrom"
                           class="admin-dashboard-date">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="lineTo"
                           class="admin-dashboard-date">
                </div>
            </div>
            <div class="relative h-44">
                <div class="absolute inset-0 grid grid-rows-4">
                    <div class="border-b border-zinc-100"></div>
                    <div class="border-b border-zinc-100"></div>
                    <div class="border-b border-zinc-100"></div>
                    <div></div>
                </div>
                <svg class="relative h-full w-full" viewBox="0 0 640 190" preserveAspectRatio="none" aria-hidden="true">
                    <polyline points="{{ $linePoints }}" fill="none" stroke-width="4"
                              style="stroke: var(--admin-chart-3)"
                              stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="mt-2 flex justify-between admin-muted">
                @foreach($lineGraph as $point)
                    <span>{{ $point['label'] }}</span>
                @endforeach
            </div>
        </div>

        <div class="admin-dashboard-card">
            <div class="mb-4 flex flex-col gap-3">
                <h2 class="admin-section-title">Donut chart</h2>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="donutFrom"
                           class="admin-dashboard-date min-w-0">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="donutTo"
                           class="admin-dashboard-date min-w-0">
                </div>
            </div>
            <div class="flex items-center justify-center py-2">
                <div class="relative grid h-32 w-32 place-items-center">
                    <svg class="h-32 w-32 -rotate-90" viewBox="0 0 120 120" aria-hidden="true">
                        <circle cx="60" cy="60" r="44" fill="none" stroke="#e7e5e4" stroke-width="18"/>
                        <circle cx="60" cy="60" r="44" fill="none" pathLength="100"
                                stroke-width="18" stroke-linecap="round"
                                stroke-dasharray="{{ $donutActive }} {{ 100 - $donutActive }}"
                                style="stroke: var(--admin-chart-2)"/>
                        <circle cx="60" cy="60" r="44" fill="none" pathLength="100"
                                stroke-width="18" stroke-linecap="round"
                                stroke-dasharray="{{ $donutPending }} {{ 100 - $donutPending }}"
                                stroke-dashoffset="-{{ $donutActive }}"
                                style="stroke: var(--admin-chart-4)"/>
                        <circle cx="60" cy="60" r="44" fill="none" pathLength="100"
                                stroke-width="18" stroke-linecap="round"
                                stroke-dasharray="{{ $donutInactive }} {{ 100 - $donutInactive }}"
                                stroke-dashoffset="-{{ $donutPendingEnd }}"
                                style="stroke: var(--admin-chart-1)"/>
                    </svg>
                    <div class="absolute inset-0 grid place-items-center">
                        <div class="text-center">
                            <p class="admin-page-title">{{ $conversionRate }}%</p>
                            <p class="admin-muted">active</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                @foreach($donutGraph as $segment)
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-1.5 text-zinc-600">
                            <span class="h-2.5 w-2.5 rounded-sm {{ $segment['class'] }}"></span>
                            {{ $segment['label'] }}
                        </span>
                        <span class="text-zinc-400">{{ $segment['percent'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-dashboard-card">
            <div class="mb-4 flex flex-col gap-3">
                <h2 class="admin-section-title">Horizontal bar</h2>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="horizontalFrom"
                           class="admin-dashboard-date min-w-0">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="horizontalTo"
                           class="admin-dashboard-date min-w-0">
                </div>
            </div>
            <div class="space-y-4">
                @foreach($horizontalGraph as $item)
                    <div>
                        <div class="mb-1 flex items-center justify-between">
                            <span class="admin-label">{{ $item['label'] }}</span>
                            <span class="admin-muted">{{ number_format($item['total']) }}</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-zinc-100">
                            <div class="h-full rounded-full" style="width: {{ $item['percent'] }}%; background-color: var(--admin-chart-2)"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="admin-dashboard-card lg:col-span-2">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <h2 class="admin-section-title">Area graph</h2>
                <div class="flex items-center gap-2">
                    <input type="date" wire:model.live="areaFrom"
                           class="admin-dashboard-date">
                    <span class="admin-muted">to</span>
                    <input type="date" wire:model.live="areaTo"
                           class="admin-dashboard-date">
                </div>
            </div>
            <div class="relative h-40 overflow-hidden rounded-lg" style="background-color: var(--admin-chart-3-soft)">
                <svg class="h-full w-full" viewBox="0 0 640 180" preserveAspectRatio="none" aria-hidden="true">
                    <path d="M{{ $areaPoints }} L640 180 L0 180 Z" style="fill: var(--admin-chart-5-soft)"/>
                    <polyline points="{{ $areaPoints }}" fill="none" stroke-width="4"
                              style="stroke: var(--admin-chart-5)"
                              stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="admin-dashboard-card lg:col-span-3">
            <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                <h2 class="admin-section-title">Stacked bar graph</h2>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2">
                        <input type="date" wire:model.live="stackedFrom"
                               class="admin-dashboard-date">
                        <span class="admin-muted">to</span>
                        <input type="date" wire:model.live="stackedTo"
                               class="admin-dashboard-date">
                    </div>
                    <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1.5 admin-muted"><span
                            class="h-2.5 w-2.5 rounded-sm admin-chart-2"></span>Active</span>
                        <span class="flex items-center gap-1.5 admin-muted"><span
                                class="h-2.5 w-2.5 rounded-sm admin-chart-4"></span>Pending</span>
                        <span class="flex items-center gap-1.5 admin-muted"><span
                                class="h-2.5 w-2.5 rounded-sm admin-chart-1"></span>Inactive</span>
                    </div>
                </div>
            </div>
            <div class="space-y-3">
                @foreach($stackedGraph as $month)
                    <div class="grid items-center gap-3 sm:grid-cols-[72px_1fr]">
                        <span class="admin-link-label text-zinc-500">{{ $month['label'] }}</span>
                        <div class="flex h-5 overflow-hidden rounded-full bg-zinc-100">
                            <div class="admin-chart-2" style="width: {{ $month['active'] }}%"></div>
                            <div class="admin-chart-4" style="width: {{ $month['pending'] }}%"></div>
                            <div class="admin-chart-1" style="width: {{ $month['inactive'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm ring-1 ring-zinc-100/70">
        <div class="flex items-center justify-between border-b border-zinc-100 bg-zinc-50 px-5 py-3">
            <h2 class="admin-section-title">Recent users</h2>
            <a href="{{ route('users.index') }}" class="admin-link-label text-zinc-950 hover:text-zinc-600">View
                all</a>
        </div>

        @if($recentUsers->isEmpty())
            <div class="px-6 py-10 text-center">
                <p class="admin-body-muted">No users yet.</p>
            </div>
        @else
            <div class="hidden md:block">
                <table class="admin-table">
                    <thead>
                    <tr class="border-b border-zinc-100 text-left">
                        <th class="px-5 py-3 admin-link-label text-zinc-500">Name</th>
                        <th class="px-5 py-3 admin-link-label text-zinc-500">Email</th>
                        <th class="px-5 py-3 admin-link-label text-zinc-500">Role</th>
                        <th class="px-5 py-3 admin-link-label text-zinc-500">Status</th>
                        <th class="px-5 py-3 admin-link-label text-zinc-500">Joined</th>
                    </tr>
                    </thead>
                    <tbody class="admin-table-body">
                    @foreach($recentUsers as $user)
                        <tr>
                            <td class="px-5 py-3 text-sm font-medium leading-5 text-zinc-950">{{ $user->name }}</td>
                            <td class="px-5 py-3 admin-caption">{{ $user->email }}</td>
                            <td class="px-5 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles->sortBy('name') as $role)
                                        <span
                                            class="inline-flex rounded-full px-2 py-0.5 admin-badge shadow-sm {{ $role->name === 'admin' ? 'bg-zinc-950 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                    @empty
                                        <span class="admin-muted">—</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                    <span
                                        class="inline-flex rounded-full px-2 py-0.5 admin-badge shadow-sm {{ $user->is_active ? 'text-white admin-chart-2' : 'text-white admin-chart-1' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                            </td>
                            <td class="px-5 py-3 admin-muted">{{ $user->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-zinc-100 md:hidden">
                @foreach($recentUsers as $user)
                    <div class="px-4 py-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium leading-5 text-zinc-950">{{ $user->name }}</p>
                                <p class="truncate admin-muted">{{ $user->email }}</p>
                            </div>
                            <span
                                class="shrink-0 rounded-full px-2 py-0.5 admin-badge shadow-sm {{ $user->is_active ? 'text-white admin-chart-2' : 'text-white admin-chart-1' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

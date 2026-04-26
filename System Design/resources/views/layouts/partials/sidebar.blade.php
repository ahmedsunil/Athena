@php
    $currentRoute = request()->route()?->getName() ?? '';
    $user = auth()->user();
    $initial = strtoupper(substr($user?->name ?? 'A', 0, 1));

    $navGroups = [
        [
            'label' => 'Overview',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'dashboard',
                    'href'  => route('dashboard'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 3v7h7V3H3Zm11 0v7h7V3h-7ZM3 14v7h7v-7H3Zm11 0v7h7v-7h-7Z"/>',
                ],
            ],
        ],
        [
            'label' => 'Components',
            'items' => [
                [
                    'label' => 'Users',
                    'route' => 'users',
                    'href'  => route('users.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                ],
            ],
        ],
    ];
@endphp

<div class="flex h-full min-h-0 flex-col bg-white" x-data="{ accountOpen: false }">
    <div class="flex items-center gap-3 px-4 py-5">
        @if(file_exists(public_path('images/logo.png')))
            <img src="/images/logo.png" alt="{{ config('app.name') }}" class="h-11 w-11 rounded-xl object-contain">
        @else
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-zinc-950 text-sm font-bold text-white shadow-sm">
                UI
            </div>
        @endif
        <div class="min-w-0">
            <p class="truncate text-base font-bold text-zinc-950">{{ config('app.name') }}</p>
            <p class="truncate text-xs text-zinc-400">Admin system</p>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-6 overflow-y-auto px-4 pb-5">
        @foreach($navGroups as $group)
            <div>
                <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-widest text-zinc-400">
                    {{ $group['label'] }}
                </p>
                <div class="space-y-1">
                    @foreach($group['items'] as $item)
                        @php $isActive = str_starts_with($currentRoute, $item['route']); @endphp
                        <a href="{{ $item['href'] }}"
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                                  {{ $isActive ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' }}">
                            <span class="shrink-0 {{ $isActive ? 'text-zinc-950' : 'text-zinc-400' }}">
                                <svg class="h-[15px] w-[15px]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    {!! $item['icon'] !!}
                                </svg>
                            </span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="mt-auto shrink-0 border-t border-zinc-100 bg-white px-4 pb-4 pt-3">
        <div x-show="accountOpen"
             x-transition
             class="overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 px-3 py-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-100 text-sm font-semibold text-zinc-950">
                    {{ $initial }}
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold text-zinc-950">{{ $user?->name ?? 'Admin' }}</p>
                    <p class="truncate text-xs text-zinc-500">{{ $user?->email ?? 'admin@example.com' }}</p>
                </div>
            </div>

            <div class="border-t border-zinc-200 px-3 py-2">
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-2.5 rounded-lg px-1 py-2 text-sm font-semibold text-zinc-950 transition-colors hover:bg-zinc-50 hover:text-zinc-700">
                    <svg class="h-4 w-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.4 1.08V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8.6 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.08-.4H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 8.6a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .4-1.08V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15.4 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.17.31.39.58.6.8.31.34.72.6 1.08.6H21a2 2 0 0 1 0 4h-.09A1.65 1.65 0 0 0 19.4 15Z"/>
                    </svg>
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2.5 rounded-lg px-1 py-2 text-left text-sm font-semibold text-zinc-950 transition-colors hover:bg-zinc-50 hover:text-zinc-700">
                        <svg class="h-4 w-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 17 5-5-5-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <button type="button"
                class="mt-3 flex w-full items-center justify-between rounded-lg px-2 py-2 text-left transition-colors hover:bg-zinc-100"
                :aria-expanded="accountOpen.toString()"
                @click="accountOpen = !accountOpen">
            <div class="flex min-w-0 items-center gap-2.5">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-zinc-200 bg-zinc-100 text-xs font-semibold text-zinc-950">
                    {{ $initial }}
                </div>
                <span class="truncate text-sm font-semibold text-zinc-500">{{ $user?->name ?? 'Admin' }}</span>
            </div>
            <svg class="h-4 w-4 shrink-0 text-zinc-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m7 15 5 5 5-5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="m7 9 5-5 5 5"/>
            </svg>
        </button>
    </div>
</div>

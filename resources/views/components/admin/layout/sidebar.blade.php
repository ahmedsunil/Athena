{{--
    Layout: Sidebar
    ---------------
    Navigation sidebar with grouped nav items + user footer.
    Shared between the desktop aside and the mobile drawer.

    Customise:
        1. Replace the logo <img> src with your own logo path.
        2. Edit $navGroups to match your routes.
        3. Each item needs: label, route (prefix for active detection), href, icon (maps to <x-admin.ui.nav-icon>).

    Usage:
        @include('admin-system.components.layout.sidebar')
--}}
@php
    $currentRoute = request()->route()?->getName() ?? '';

    $navGroups = [
        [
            'label' => 'Overview',
            'items' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'href' => route('dashboard'), 'icon' => 'dashboard'],
            ],
        ],
        [
            'label' => 'Manage',
            'items' => [
                // Add your resource routes here
                // ['label' => 'Orders', 'route' => 'orders', 'href' => route('orders.index'), 'icon' => 'orders'],
            ],
        ],
    ];
@endphp

{{-- Logo --}}
<div class="mx-1 flex h-24 shrink-0 items-center border-b border-zinc-100 px-4">
    <img src="/images/logo.png" alt="{{ config('app.name') }}" class="h-16 w-auto object-contain">
</div>

{{-- Nav groups --}}
<nav class="flex-1 space-y-5 overflow-y-auto px-2 py-4">
    @foreach($navGroups as $group)
        <div>
            <p class="mb-1 px-3 admin-eyebrow">
                {{ $group['label'] }}
            </p>
            <div class="space-y-0.5">
                @foreach($group['items'] as $item)
                    @php $isActive = str_starts_with($currentRoute, $item['route']); @endphp
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm font-medium transition-colors
                              {{ $isActive ? 'bg-zinc-100 text-zinc-950' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-950' }}">
                        <span class="shrink-0 {{ $isActive ? 'text-zinc-950' : 'text-zinc-400' }}">
                            {{-- Replace with your icon component or inline SVG --}}
                            <svg class="h-[15px] w-[15px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </span>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>

{{-- Footer: website link + user profile + logout --}}
<div class="shrink-0 border-t border-zinc-100 px-2 py-2">
    <a href="{{ url('/') }}" target="_blank"
       class="mb-1 flex items-center gap-2.5 rounded-lg px-3 py-2 admin-eyebrow transition-colors hover:bg-zinc-100 hover:text-zinc-700">
        <svg class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        View Website
    </a>
    <div class="flex items-center justify-between rounded-lg px-1 py-1">
        <a href="{{ route('profile.index') }}"
            class="flex flex-1 items-center gap-2 rounded-lg px-2 py-1 transition-colors hover:bg-zinc-100"
           title="Edit profile">
            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-zinc-100 text-xs font-semibold text-zinc-700">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <span class="max-w-20 truncate text-sm font-medium leading-5 text-zinc-700">
                {{ auth()->user()->name }}
            </span>
        </a>
        <form method="POST" action="{{ route('logout') }}" class="pr-2">
            @csrf
            <button type="submit" class="rounded p-1 text-zinc-400 transition-colors hover:text-zinc-700" title="Logout">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </div>
</div>

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
            'label' => 'CMS',
            'items' => [
                [
                    'label'        => 'Home',
                    'route'        => 'cms.school-profile',
                    'href'         => route('cms.school-profile'),
                    'activeRoutes' => ['cms.school-profile', 'cms.footer-links', 'cms.home.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>',
                ],
                [
                    'label'        => 'About',
                    'route'        => 'cms.about.mission',
                    'href'         => route('cms.about.mission'),
                    'activeRoutes' => ['cms.about.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/>',
                ],
                [
                    'label'        => 'Events',
                    'route'        => 'cms.events.index',
                    'href'         => route('cms.events.index'),
                    'activeRoutes' => ['cms.events.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
                ],
                [
                    'label'        => 'Announcements',
                    'route'        => 'cms.announcements.index',
                    'href'         => route('cms.announcements.index'),
                    'activeRoutes' => ['cms.announcements.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022 23.848 23.848 0 0 0 5.455 1.31m5.714 0a3 3 0 1 1-5.714 0"/>',
                ],
                [
                    'label'        => 'Academics',
                    'route'        => 'cms.academics.overview',
                    'href'         => route('cms.academics.overview'),
                    'activeRoutes' => ['cms.academics.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>',
                ],
                [
                    'label'        => 'Student Life',
                    'route'        => 'cms.student-life.clubs',
                    'href'         => route('cms.student-life.clubs'),
                    'activeRoutes' => ['cms.student-life.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
                ],
                [
                    'label'        => 'Gallery',
                    'route'        => 'cms.gallery.index',
                    'href'         => route('cms.gallery.index'),
                    'activeRoutes' => ['cms.gallery.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>',
                ],
                [
                    'label'        => 'Digital Services',
                    'route'        => 'cms.digital-services.documents',
                    'href'         => route('cms.digital-services.documents'),
                    'activeRoutes' => ['cms.digital-services.'],
                    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"/>',
                ],
            ],
        ],
        [
            'label' => 'User management',
            'items' => [
                [
                    'label' => 'Users',
                    'route' => 'users',
                    'href'  => route('users.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 3.13a4 4 0 0 1 0 7.75"/>',
                ],
                [
                    'label' => 'Roles',
                    'route' => 'roles',
                    'href'  => route('roles.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>',
                ],
            ],
        ],
        [
            'label' => 'App Management',
            'items' => [
                [
                    'label' => 'Links',
                    'route' => 'cms.links',
                    'href'  => route('cms.links.index'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"/>',
                ],
                [
                    'label' => 'Icons',
                    'route' => 'cms.icons',
                    'href'  => route('cms.icons.index'),
                    'icon'  => '<circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h8M12 8v8"/>',
                ],
                [
                    'label' => 'Activity Log',
                    'route' => 'app.activity',
                    'href'  => route('app.activity'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z"/>',
                ],
                [
                    'label' => 'System Settings',
                    'route' => 'app.settings',
                    'href'  => route('app.settings'),
                    'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>',
                ],
            ],
        ],
    ];
@endphp

<div class="flex h-full min-h-0 flex-col bg-black text-slate-100" x-data="{ accountOpen: false }">
    <div class="flex items-center gap-3 px-4 py-5">
        @if(file_exists(public_path('images/app-mark.webp')))
            <img src="{{ asset('images/app-mark.webp') }}" alt="{{ config('app.name') }}" class="h-14 w-14 rounded-xl object-contain">
        @else
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-sm font-bold text-black shadow-sm">
                HS
            </div>
        @endif
        <div class="min-w-0">
            <p class="truncate text-base font-bold text-white">{{ config('app.name') }}</p>
            <p class="truncate text-xs text-white/60">Admin system</p>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-6 overflow-y-auto px-4 pb-5 pt-3">
        @foreach($navGroups as $group)
            <div>
                <p class="mb-2 px-3 text-[10px] font-medium uppercase tracking-widest text-white/60">
                    {{ $group['label'] }}
                </p>

                <div class="space-y-1">
                    @foreach($group['items'] as $item)
                        @php
                            $activeRoutes = $item['activeRoutes'] ?? [$item['route']];
                            $isActive = collect($activeRoutes)->contains(fn($r) => str_starts_with($currentRoute, $r));
                        @endphp
                        <a href="{{ $item['href'] }}" wire:navigate
                           class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-[13px] font-medium transition-colors
                                      {{ $isActive ? 'bg-white/15 text-white' : 'text-white hover:bg-white/10 hover:text-white' }}">
                                <span class="shrink-0 text-white">
                                    <svg class="h-[15px] w-[15px]" fill="none" stroke="currentColor" stroke-width="1.8"
                                         viewBox="0 0 24 24">
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

    <div class="mt-auto shrink-0 border-t border-white/10 bg-black px-3 pb-2 pt-2">
        <div x-show="accountOpen"
             x-transition
             x-cloak
             class="overflow-hidden rounded-lg border border-white/10 bg-black shadow-sm">
            <div class="flex items-center gap-2.5 px-2.5 py-2.5">
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-white/20 bg-white/10 admin-button-label text-white">
                    {{ $initial }}
                </div>
                <div class="min-w-0">
                    <p class="truncate admin-button-label text-white">{{ $user?->name ?? 'Admin' }}</p>
                    <p class="truncate text-xs text-white/60">{{ $user?->email ?? 'admin@example.com' }}</p>
                </div>
            </div>

            <div class="border-t border-white/10 px-2 py-1.5">
                <a href="{{ route('profile.index') }}" wire:navigate
                   class="flex items-center gap-2.5 rounded-md px-2 py-1.5 text-sm font-medium text-white transition-colors hover:bg-white/10 hover:text-white">
                    <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                         viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06A1.65 1.65 0 0 0 15 19.4a1.65 1.65 0 0 0-1 .6 1.65 1.65 0 0 0-.4 1.08V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8.6 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.6 15a1.65 1.65 0 0 0-.6-1 1.65 1.65 0 0 0-1.08-.4H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 8.6a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.6a1.65 1.65 0 0 0 1-.6 1.65 1.65 0 0 0 .4-1.08V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15.4 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.17.31.39.58.6.8.31.34.72.6 1.08.6H21a2 2 0 0 1 0 4h-.09A1.65 1.65 0 0 0 19.4 15Z"/>
                    </svg>
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex w-full items-center gap-2.5 rounded-md px-2 py-1.5 text-left text-sm font-medium text-white transition-colors hover:bg-white/10 hover:text-white">
                        <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="1.8"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16 17 5-5-5-5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12H9"/>
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>

        <button type="button"
                class="flex w-full items-center justify-between rounded-lg px-2 py-1.5 text-left transition-colors hover:bg-white/10"
                :aria-expanded="accountOpen.toString()"
                @click="accountOpen = !accountOpen">
            <div class="flex min-w-0 items-center gap-2.5">
                <div
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-white/20 bg-white/10 admin-button-label text-white">
                    {{ $initial }}
                </div>
                <span class="truncate text-[13px] font-semibold text-white">{{ $user?->name ?? 'Admin' }}</span>
            </div>
            <svg class="h-4 w-4 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="m7 15 5 5 5-5"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="m7 9 5-5 5 5"/>
            </svg>
        </button>
    </div>
</div>

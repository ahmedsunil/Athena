<div>

    {{-- Hero --}}
    @if($slides->isNotEmpty())
        <section class="relative min-h-[720px] overflow-hidden bg-slate-950" id="hero-slider">
            @foreach($slides as $i => $slide)
                <div
                    class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-out {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                             class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-[#002366]"></div>
                    @endif
                    <div class="absolute inset-0 bg-slate-950/70"></div>

                    <div
                        class="relative min-h-[720px] max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-36 flex items-center justify-center">
                        <div class="mx-auto max-w-5xl text-center" data-reveal="scale">
                            <h1 class="mx-auto max-w-5xl break-words text-4xl sm:text-6xl lg:text-7xl font-black text-white leading-[0.95] mb-6 [overflow-wrap:anywhere]"
                                data-en="{{ $slide->getTranslation('title', 'en', false) }}"
                                data-dv="{{ $slide->getTranslation('title', 'dv', false) ?: $slide->getTranslation('title', 'en', false) }}">{{ $slide->title }}</h1>
                            @php $descEn = $slide->getTranslation('description', 'en', false); $descDv = $slide->getTranslation('description', 'dv', false) ?: $descEn; @endphp
                            @if($slide->description)
                                <p class="mx-auto max-w-2xl text-white/80 text-base sm:text-lg mb-9 leading-relaxed"
                                   data-en="{{ $descEn }}"
                                   data-dv="{{ $descDv }}">{{ $slide->description }}</p>
                            @endif
                            @if($slide->button_1_label || $slide->button_2_label)
                                <div class="flex flex-wrap justify-center gap-3">
                                    @if($slide->button_1_label)
                                        <a href="{{ $slide->button_1_link_key ?: '#' }}"
                                           class="inline-flex min-h-12 items-center justify-center gap-2 bg-white text-[#002366] hover:bg-white/90 font-bold px-7 py-3 rounded-full shadow-xl shadow-black/20 transition-colors text-sm"
                                           data-en="{{ $slide->getTranslation('button_1_label', 'en', false) }}"
                                           data-dv="{{ $slide->getTranslation('button_1_label', 'dv', false) ?: $slide->getTranslation('button_1_label', 'en', false) }}">{{ $slide->button_1_label }}</a>
                                    @endif
                                    @if($slide->button_2_label)
                                        <a href="{{ $slide->button_2_link_key ?: '#' }}"
                                           class="inline-flex min-h-12 items-center justify-center gap-2 bg-transparent hover:bg-white/10 text-white font-bold px-7 py-3 rounded-full border border-white/30 transition-colors text-sm"
                                           data-en="{{ $slide->getTranslation('button_2_label', 'en', false) }}"
                                           data-dv="{{ $slide->getTranslation('button_2_label', 'dv', false) ?: $slide->getTranslation('button_2_label', 'en', false) }}">{{ $slide->button_2_label }}</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($slides->count() > 1)
                <div
                    class="absolute bottom-16 left-1/2 z-10 flex w-[min(92vw,34rem)] -translate-x-1/2 items-center gap-4">
                    <span class="text-xs font-bold tabular-nums text-white/70">01</span>
                    <div class="flex flex-1 items-center gap-2">
                        @foreach($slides as $i => $slide)
                            <button onclick="heroGoTo({{ $i }})"
                                    aria-label="Go to slide {{ $i + 1 }}"
                                    class="hero-dot h-1.5 flex-1 rounded-full transition-all duration-300 {{ $i === 0 ? 'bg-white' : 'bg-white/35 hover:bg-white/65' }}"></button>
                        @endforeach
                    </div>
                    <span
                        class="text-xs font-bold tabular-nums text-white/70">{{ str_pad((string) $slides->count(), 2, '0', STR_PAD_LEFT) }}</span>
                </div>
                <button onclick="heroPrev()"
                        aria-label="Previous slide"
                        class="absolute left-6 top-1/2 z-10 hidden -translate-y-1/2 rounded-full border border-white/20 bg-white/10 p-3 text-white shadow-lg backdrop-blur-md transition-colors hover:bg-white/20 sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="heroNext()"
                        aria-label="Next slide"
                        class="absolute right-6 top-1/2 z-10 hidden -translate-y-1/2 rounded-full border border-white/20 bg-white/10 p-3 text-white shadow-lg backdrop-blur-md transition-colors hover:bg-white/20 sm:flex">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            @endif
        </section>
        <script>
            (function () {
                var current = 0;
                var slides = document.querySelectorAll('.hero-slide');
                var dots = document.querySelectorAll('.hero-dot');
                var total = slides.length;
                var timer;

                function setDot(index, active) {
                    if (!dots[index]) return;
                    dots[index].className = 'hero-dot h-1.5 flex-1 rounded-full transition-all duration-300 ' + (active ? 'bg-white' : 'bg-white/35 hover:bg-white/65');
                }

                function goTo(n) {
                    slides[current].classList.replace('opacity-100', 'opacity-0');
                    slides[current].classList.add('pointer-events-none');
                    setDot(current, false);
                    current = (n + total) % total;
                    slides[current].classList.replace('opacity-0', 'opacity-100');
                    slides[current].classList.remove('pointer-events-none');
                    setDot(current, true);
                }

                function next() {
                    goTo(current + 1);
                }

                function prev() {
                    goTo(current - 1);
                }

                function startTimer() {
                    timer = setInterval(next, 5000);
                }

                function resetTimer() {
                    clearInterval(timer);
                    startTimer();
                }

                window.heroGoTo = function (n) {
                    goTo(n);
                    resetTimer();
                };
                window.heroNext = function () {
                    next();
                    resetTimer();
                };
                window.heroPrev = function () {
                    prev();
                    resetTimer();
                };
                if (total > 1) startTimer();
            })();
        </script>
    @endif

    {{-- Stats --}}
    @if($stats->isNotEmpty())
        <section class="bg-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @php
                    $statIcons = ['Users', 'Briefcase', 'Calendar', 'BookOpen'];
                @endphp
                <div
                    class="grid grid-cols-2 lg:grid-cols-{{ min($stats->count(), 4) }} gap-px overflow-hidden rounded-sm border border-slate-200 bg-slate-200 text-center">
                    @foreach($stats as $stat)
                        <div class="bg-white/95 px-5 py-6 backdrop-blur" data-reveal="scale"
                             style="--reveal-delay: {{ $loop->index * 80 }}ms">
                            <div class="mx-auto mb-3 flex h-9 w-9 items-center justify-center rounded-sm bg-[#002366]/5 text-[#002366]">
                                {!! svg_icon($statIcons[$loop->index % count($statIcons)], 'h-4 w-4') !!}
                            </div>
                            <p class="text-3xl sm:text-4xl font-black text-[#002366]">{{ $stat->value }}</p>
                            <p class="text-slate-500 text-sm font-semibold mt-1">{{ $stat->title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Quick Access --}}
    @if($quickAccess->isNotEmpty())
        <section class="py-16 sm:py-20 bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10" data-reveal="fade">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('home_portal_label') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_quick_access') }}</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($quickAccess as $item)
                        @php
                            $isAdmissions = $item->link_key === '/admissions' || strcasecmp($item->title, 'Admissions') === 0;
                            $quickAccessTitle = $isAdmissions ? __('common_announcements') : $item->title;
                            $quickAccessHref = $isAdmissions ? route('announcements.index') : $item->link_key;
                            $quickAccessIcon = $isAdmissions ? 'Bell' : $item->icon_key;
                            $isExternalQuickAccess = preg_match('/^https?:\/\//i', $quickAccessHref) === 1;
                        @endphp
                        <a href="{{ $quickAccessHref }}"
                           @if($isExternalQuickAccess) target="_blank" rel="noopener noreferrer" @endif
                           data-reveal="scale"
                           style="--reveal-delay: {{ $loop->index * 55 }}ms"
                           class="group flex flex-col items-center gap-3 bg-white rounded-2xl p-6 border border-slate-200 hover:border-[#002366]/20 hover:shadow-md transition-all text-center">
                            <div
                                class="w-10 h-10 rounded-xl bg-[#002366]/5 group-hover:bg-[#002366]/10 text-[#002366] flex items-center justify-center transition-colors">
                                {!! svg_icon($quickAccessIcon) !!}
                            </div>
                            <span
                                class="text-sm font-semibold text-slate-700 group-hover:text-slate-900">{{ $quickAccessTitle }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Featured Events --}}
    @if($featuredEvents->isNotEmpty())
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10 text-center" data-reveal="fade">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('home_events_label') }}</p>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_featured_events') }}</h2>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($featuredEvents as $event)
                        <div
                            class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow"
                            data-reveal="scale" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                            <div class="relative h-48 overflow-hidden bg-slate-100">
                                @if($event->cover_image_path)
                                    <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-400"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-slate-900/25"></div>
                                <span
                                    class="absolute top-3 left-3 text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full bg-[#002366] text-white">
                                    {{ __('common_status_' . $event->status) }}
                                </span>
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-[#002366] font-semibold mb-1">{{ $event->formatted_date_range }}</p>
                                <h3 class="font-bold text-slate-900 mb-1">{{ $event->title }}</h3>
                                <p class="text-xs text-slate-500 mb-3 flex items-start gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->location }}
                                </p>
                                <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $event->short_description }}</p>
                                <a href="{{ route('events.show', $event->slug) }}"
                                   class="block w-full text-center text-sm font-semibold text-[#002366] hover:text-[#002366] border border-[#002366]/20 hover:border-[#002366]/40 rounded-xl py-2 transition-colors">
                                    {{ __('events_view_details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-8 flex justify-center" data-reveal="fade">
                    <a href="{{ route('events.index') }}"
                       class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#002366] hover:text-[#001a4d]">
                        {{ __('home_all_events') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- Principal's Message --}}
    @if($profile->principal_name || $profile->principal_message)
        <section class="py-16 sm:py-20 bg-[#F3F6FA]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-2xl border border-[#D8E0EC] overflow-hidden shadow-sm" data-reveal="scale">
                    <div class="flex flex-col md:flex-row">
                        @if($profile->principal_photo_path)
                            <div
                                class="flex w-full shrink-0 items-center justify-center bg-[#002366]/5 px-8 py-8 md:w-72 md:px-7 md:py-8">
                                <div
                                    class="h-36 w-36 overflow-hidden rounded-full bg-slate-100 ring-4 ring-white shadow-xl shadow-slate-900/10 sm:h-40 sm:w-40 md:h-80 md:w-52 md:rounded-sm md:ring-1 md:ring-[#D8E0EC]">
                                    <img src="{{ $profile->principal_photo_url }}" alt="{{ $profile->principal_name }}"
                                         class="h-full w-full object-cover object-top">
                                </div>
                            </div>
                        @endif
                        <div class="flex-1 p-8 flex flex-col justify-center">
                            <p class="mb-4 text-xs font-bold uppercase tracking-widest text-[#002366]">{{ __('home_principals_welcome') }}</p>
                            @if($profile->principal_message)
                                <p class="text-slate-700 leading-relaxed italic text-lg">
                                    "{{ $profile->principal_message }}"</p>
                            @endif
                            <div class="mt-6 pt-5 border-t border-[#D8E0EC]">
                                @if($profile->principal_name)
                                    <p class="font-bold text-slate-900">{{ $profile->principal_name }}</p>
                                @endif
                                @if($profile->principal_designation)
                                    <p class="text-sm text-slate-500">{{ $profile->principal_designation }}
                                        , {{ $profile->school_name }}</p>
                                @endif
                            </div>
                            <a href="{{ route('about') }}"
                               class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-[#002366] hover:text-[#001a4d]">
                                {{ __('home_read_full_message') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        @php $tChunks = $testimonials->chunk(4); @endphp
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12" data-reveal="fade">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('home_community_label') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_what_people_say') }}</h2>
                </div>

                @foreach($tChunks as $ci => $chunk)
                    <div
                        class="t-page {{ $ci === 0 ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($chunk as $t)
                            @php
                                $testimonialName = app()->getLocale() === 'dv' && $t->name_dv ? $t->name_dv : $t->name;
                            @endphp
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 flex flex-col"
                                 data-reveal="scale" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                                <p class="text-sm text-slate-700 leading-relaxed italic flex-1">"{{ $t->message }}"</p>
                                <div class="mt-5 flex items-center gap-3">
                                    @if($t->photo_path)
                                        <img src="{{ $t->photo_url }}" alt="{{ $testimonialName }}"
                                             class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-[#002366]/10 text-[#002366] flex items-center justify-center flex-shrink-0 text-sm font-bold">
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $testimonialName }}</p>
                                        <p class="text-xs text-slate-500">{{ $t->current_designation }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                @if($tChunks->count() > 1)
                    <div class="flex justify-center gap-2 mt-8" id="t-dots">
                        @foreach($tChunks as $ci => $chunk)
                            <button onclick="tGoTo({{ $ci }})"
                                    class="t-dot w-2 h-2 rounded-full transition-colors {{ $ci === 0 ? 'bg-[#002366]' : 'bg-slate-300' }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
        <script>
            (function () {
                var pages = document.querySelectorAll('.t-page');
                var dots = document.querySelectorAll('.t-dot');
                var total = pages.length;
                var current = 0;

                window.tGoTo = function (n) {
                    pages[current].classList.add('hidden');
                    if (dots[current]) dots[current].className = 't-dot w-2 h-2 rounded-full transition-colors bg-slate-300';
                    current = (n + total) % total;
                    pages[current].classList.remove('hidden');
                    if (dots[current]) dots[current].className = 't-dot w-2 h-2 rounded-full transition-colors bg-[#002366]';
                };

                if (total > 1) setInterval(function () {
                    tGoTo(current + 1);
                }, 5000);
            })();
        </script>
    @endif

    {{-- Contact --}}
    <section id="contact" class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-reveal="fade">
                <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('home_contact_label') }}</p>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_contact_heading') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4" data-reveal="left">
                    @php
                        $contactEmail = $profile->email ?: 'info@hulhudhuffaaruschool.edu.mv';
                        $contactPhone = $profile->phone ?: '+960 658-0000';
                        $contactPhoneHref = preg_replace('/[^\d+]/', '', $contactPhone);
                        $mapUrl = 'https://maps.app.goo.gl/LSx66yU4VQqPvLWq7';
                    @endphp
                    @if($contactEmail)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-[#002366]/10 text-[#002366] flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('Mail') !!}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_email') }}</p>
                                <a href="mailto:{{ $contactEmail }}"
                                   class="text-sm text-slate-600 transition-colors hover:text-[#002366]">{{ $contactEmail }}</a>
                            </div>
                        </div>
                    @endif
                    @if($contactPhone)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-[#002366]/10 text-[#002366] flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('Phone') !!}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_phone') }}</p>
                                <a href="tel:{{ $contactPhoneHref }}"
                                   class="text-sm text-slate-600 transition-colors hover:text-[#002366]">{{ $contactPhone }}</a>
                            </div>
                        </div>
                    @endif
                    @php
                        $locationParts = collect([
                            $profile->getTranslation('island', app()->getLocale(), false),
                            $profile->getTranslation('atoll', app()->getLocale(), false),
                            $profile->getTranslation('country', app()->getLocale(), false),
                        ])->filter()->join(', ');
                    @endphp
                    @if($locationParts)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
                                {!! svg_icon('MapPin') !!}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_address') }}</p>
                                <a href="{{ $mapUrl }}" target="_blank" rel="noopener"
                                   class="text-sm text-slate-600 transition-colors hover:text-[#002366]">{{ $locationParts }}</a>
                            </div>
                        </div>
                    @endif
                </div>
                <form wire:submit="submitContact" class="space-y-4" data-reveal="right" style="--reveal-delay: 120ms">
                    @if($contactSent)
                        <div
                            class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                            {{ __('home_form_success') }}
                        </div>
                    @endif

                    <div>
                        <input type="text" wire:model="contactName" placeholder="{{ __('home_form_name_placeholder') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#002366] text-sm">
                        @error('contactName') <p
                            class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <input type="email" wire:model="contactEmail"
                               placeholder="{{ __('home_form_email_placeholder') }}"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#002366] text-sm">
                        @error('contactEmail') <p
                            class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                    <textarea wire:model="contactMessage" rows="4"
                              placeholder="{{ __('home_form_message_placeholder') }}"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#002366] text-sm resize-none"></textarea>
                        @error('contactMessage') <p
                            class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" wire:target="submitContact"
                            class="w-full bg-[#002366] hover:bg-[#001a4d] text-white font-semibold py-3 rounded-xl transition-colors text-sm">
                        <span wire:loading.remove wire:target="submitContact">{{ __('home_form_send') }}</span>
                        <span wire:loading wire:target="submitContact">{{ __('home_form_sending') }}</span>
                    </button>
                </form>
            </div>
        </div>
    </section>

</div>

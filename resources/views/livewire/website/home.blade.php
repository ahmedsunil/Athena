<div>

    {{-- Hero --}}
    @if($slides->isNotEmpty())
        <section class="relative h-[55vh] min-h-105 overflow-hidden" id="hero-slider">
            @foreach($slides as $i => $slide)
                <div
                    class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
                    @if($slide->image_path)
                        <img src="{{ $slide->image_url }}" alt="{{ $slide->title }}"
                             class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-slate-900"></div>
                    @endif
                    <div
                        class="absolute inset-0 {{ app()->getLocale() === 'dv' ? 'bg-gradient-to-l' : 'bg-gradient-to-r' }} from-slate-950/80 via-slate-900/50 to-transparent" id="slide-gradient"></div>
                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center pt-16">
                        <div class="max-w-xl" data-reveal="left">
                            <p class="text-xs font-bold uppercase tracking-widest text-rose-400 mb-3">
                                <span data-lang-key="school_name"
                                      data-en="Hulhudhuffaaru School">{{ __('school_name') }}</span>
                            </p>
                            <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-5"
                                data-en="{{ $slide->getTranslation('title', 'en', false) }}"
                                data-dv="{{ $slide->getTranslation('title', 'dv', false) ?: $slide->getTranslation('title', 'en', false) }}">{{ $slide->title }}</h1>
                            @php $descEn = $slide->getTranslation('description', 'en', false); $descDv = $slide->getTranslation('description', 'dv', false) ?: $descEn; @endphp
                            @if($slide->description)
                                <p class="text-slate-300 text-base mb-8 leading-relaxed"
                                   data-en="{{ $descEn }}"
                                   data-dv="{{ $descDv }}">{{ $slide->description }}</p>
                            @endif
                            @if($slide->button_1_label || $slide->button_2_label)
                                <div class="flex flex-wrap gap-3">
                                    @if($slide->button_1_label)
                                        <a href="{{ $slide->button_1_link_key ?: '#' }}"
                                           class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm"
                                           data-en="{{ $slide->getTranslation('button_1_label', 'en', false) }}"
                                           data-dv="{{ $slide->getTranslation('button_1_label', 'dv', false) ?: $slide->getTranslation('button_1_label', 'en', false) }}">{{ $slide->button_1_label }}</a>
                                    @endif
                                    @if($slide->button_2_label)
                                        <a href="{{ $slide->button_2_link_key ?: '#' }}"
                                           class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl border border-white/20 transition-colors text-sm"
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
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    @foreach($slides as $i => $slide)
                        <button onclick="heroGoTo({{ $i }})"
                                class="hero-dot w-2 h-2 rounded-full transition-colors {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"></button>
                    @endforeach
                </div>
                <button onclick="heroPrev()"
                        class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button onclick="heroNext()"
                        class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white transition-colors">
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

                function goTo(n) {
                    slides[current].classList.replace('opacity-100', 'opacity-0');
                    slides[current].classList.add('pointer-events-none');
                    if (dots[current]) dots[current].classList.replace('bg-white', 'bg-white/40');
                    current = (n + total) % total;
                    slides[current].classList.replace('opacity-0', 'opacity-100');
                    slides[current].classList.remove('pointer-events-none');
                    if (dots[current]) dots[current].classList.replace('bg-white/40', 'bg-white');
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
        <section class="bg-rose-600 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 lg:grid-cols-{{ min($stats->count(), 4) }} gap-6 text-center text-white">
                    @foreach($stats as $stat)
                        <div data-reveal="scale" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                            <p class="text-3xl sm:text-4xl font-black">{{ $stat->value }}</p>
                            <p class="text-rose-200 text-sm font-medium mt-1">{{ $stat->title }}</p>
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
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('home_portal_label') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_quick_access') }}</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @foreach($quickAccess as $item)
                        @php
                            $isAdmissions = $item->link_key === '/admissions' || strcasecmp($item->title, 'Admissions') === 0;
                            $quickAccessTitle = $isAdmissions ? 'Announcements' : $item->title;
                            $quickAccessHref = $isAdmissions ? route('announcements.index') : $item->link_key;
                            $quickAccessIcon = $isAdmissions ? 'Bell' : $item->icon_key;
                        @endphp
                        <a href="{{ $quickAccessHref }}" data-reveal="scale"
                           style="--reveal-delay: {{ $loop->index * 55 }}ms"
                           class="group flex flex-col items-center gap-3 bg-white rounded-2xl p-6 border border-slate-200 hover:border-rose-200 hover:shadow-md transition-all text-center">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 group-hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors">
                                <x-icon :key="$quickAccessIcon"/>
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
                <div class="flex items-end justify-between mb-10" data-reveal="fade">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('home_events_label') }}</p>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_featured_events') }}</h2>
                    </div>
                    <a href="{{ route('events.index') }}"
                       class="text-sm font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                        {{ __('home_all_events') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
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
                                <span class="absolute top-3 left-3 text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full
                            {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $event->status }}
                        </span>
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-sky-600 font-semibold mb-1">{{ $event->formatted_date_range }}</p>
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
                                   class="block w-full text-center text-sm font-semibold text-rose-600 hover:text-rose-700 border border-rose-200 hover:border-rose-300 rounded-xl py-2 transition-colors">
                                    {{ __('events_view_details') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Principal's Message --}}
    @if($profile->principal_name || $profile->principal_message)
        <section class="py-16 sm:py-20 bg-slate-900">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-slate-800/50 rounded-2xl border border-slate-700 overflow-hidden" data-reveal="scale">
                    <div class="flex flex-col md:flex-row">
                        @if($profile->principal_photo_path)
                            <div class="flex-shrink-0 w-full md:w-56">
                                <img src="{{ $profile->principal_photo_url }}" alt="{{ $profile->principal_name }}"
                                     class="w-full h-56 md:h-full object-cover object-top">
                            </div>
                        @endif
                        <div class="flex-1 p-8 flex flex-col justify-center">
                            <p class="text-xs font-bold uppercase tracking-widest text-rose-400 mb-4">{{ __('home_principals_welcome') }}</p>
                            @if($profile->principal_message)
                                <p class="text-slate-200 leading-relaxed italic text-lg">
                                    "{{ $profile->principal_message }}"</p>
                            @endif
                            <div class="mt-6 pt-5 border-t border-slate-700">
                                @if($profile->principal_name)
                                    <p class="font-bold text-white">{{ $profile->principal_name }}</p>
                                @endif
                                @if($profile->principal_designation)
                                    <p class="text-sm text-slate-400">{{ $profile->principal_designation }}
                                        , {{ $profile->school_name }}</p>
                                @endif
                            </div>
                            <a href="{{ route('about') }}"
                               class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-rose-400 hover:text-rose-300">
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
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('home_community_label') }}</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_what_people_say') }}</h2>
                </div>

                @foreach($tChunks as $ci => $chunk)
                    <div
                        class="t-page {{ $ci === 0 ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($chunk as $t)
                            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 flex flex-col"
                                 data-reveal="scale" style="--reveal-delay: {{ $loop->index * 70 }}ms">
                                <p class="text-sm text-slate-700 leading-relaxed italic flex-1">"{{ $t->message }}"</p>
                                <div class="mt-5 flex items-center gap-3">
                                    @if($t->photo_path)
                                        <img src="{{ $t->photo_url }}" alt="{{ $t->name }}"
                                             class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 text-sm font-bold">
                                            {{ strtoupper(substr($t->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-semibold text-slate-900">{{ $t->name }}</p>
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
                                    class="t-dot w-2 h-2 rounded-full transition-colors {{ $ci === 0 ? 'bg-rose-600' : 'bg-slate-300' }}"></button>
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
                    if (dots[current]) dots[current].className = 't-dot w-2 h-2 rounded-full transition-colors bg-rose-600';
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
                <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('home_contact_label') }}</p>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900">{{ __('home_contact_heading') }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-4" data-reveal="left">
                    @if($profile->email)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                                <x-icon key="Mail"/>
                            </div>
                            <div><p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_email') }}</p>
                                <p class="text-slate-600 text-sm">{{ $profile->email }}</p></div>
                        </div>
                    @endif
                    @if($profile->phone)
                        <div class="flex items-start gap-3">
                            <div
                                class="w-9 h-9 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center flex-shrink-0">
                                <x-icon key="Phone"/>
                            </div>
                            <div><p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_phone') }}</p>
                                <p class="text-slate-600 text-sm">{{ $profile->phone }}</p></div>
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
                                <x-icon key="MapPin"/>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900 text-sm">{{ __('home_contact_address') }}</p>
                                <p class="text-slate-600 text-sm">{{ $locationParts }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <form action="#" method="post" class="space-y-4" data-reveal="right" style="--reveal-delay: 120ms">
                    @csrf
                    <input type="text" name="name" placeholder="{{ __('home_form_name_placeholder') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                    <input type="email" name="email" placeholder="{{ __('home_form_email_placeholder') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm">
                    <textarea name="message" rows="4" placeholder="{{ __('home_form_message_placeholder') }}"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm resize-none"></textarea>
                    <button type="submit"
                            class="w-full bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3 rounded-xl transition-colors text-sm">
                        {{ __('home_form_send') }}
                    </button>
                </form>
            </div>
        </div>
    </section>

</div>

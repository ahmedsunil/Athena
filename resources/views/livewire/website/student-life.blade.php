<div>

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal="fade">
            <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('student_life_page_label') }}</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">{{ __('student_life_heading') }}</h1>
        </div>
    </section>

    {{-- Sticky tab bar --}}
    <div class="bg-white border-b border-slate-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-0 overflow-x-auto">
                @foreach([
                    ['key' => 'student-council', 'label_key' => 'student_life_tab_student_council'],
                    ['key' => 'prefects',         'label_key' => 'student_life_tab_prefects'],
                    ['key' => 'uniform-bodies',   'label_key' => 'student_life_tab_uniform_bodies'],
                ] as $tab)
                    <button wire:click="setTab('{{ $tab['key'] }}')"
                            class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors
                                   {{ $activeTab === $tab['key'] ? 'border-[#002366] text-[#002366]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                        {{ __($tab['label_key']) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Tab content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Student Council tab (Clubs & Houses) --}}
        @if($activeTab === 'student-council')
            @php
            $clubPalette = ['violet', 'pink', 'sky', 'amber', 'emerald', 'blue'];
            $clubBg  = ['violet'=>'bg-[#002366]/10','pink'=>'bg-[#B21F2D]/10','sky'=>'bg-[#002366]/10','amber'=>'bg-[#B21F2D]/10','emerald'=>'bg-emerald-100','blue'=>'bg-[#002366]/10'];
            $clubFg  = ['violet'=>'text-[#002366]','pink'=>'text-[#B21F2D]','sky'=>'text-[#002366]','amber'=>'text-[#B21F2D]','emerald'=>'text-emerald-600','blue'=>'text-[#002366]'];
            @endphp

            {{-- Clubs --}}
            <div class="mb-10">
                <h2 class="text-lg font-bold text-slate-900 mb-5">{{ __('student_life_tab_clubs') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($clubs as $i => $club)
                        @php $colour = $clubPalette[$i % count($clubPalette)]; @endphp
                        <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-md transition-shadow"
                             data-reveal="scale" style="--reveal-delay: {{ ($i % 6) * 60 }}ms">
                            <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-5 bg-white shadow-sm ring-1 ring-inset ring-slate-200 overflow-hidden">
                                @if($club->logo_path)
                                    <img src="{{ $club->logo_url }}" alt="{{ $club->name }}" class="w-full h-full object-contain p-2">
                                @else
                                    <div class="w-full h-full flex items-center justify-center {{ $clubBg[$colour] }} {{ $clubFg[$colour] }}">
                                        <span class="text-2xl font-black">{{ $club->initials }}</span>
                                    </div>
                                @endif
                            </div>
                            <h3 class="font-bold text-slate-900 mb-2">{{ $club->name }}</h3>
                            @if($club->meeting_schedule)
                                <p class="text-xs text-[#002366] font-semibold mb-3 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $club->meeting_schedule }}
                                </p>
                            @endif
                            @if($club->description)
                                <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $club->description }}</p>
                            @endif
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <div>
                                    <p class="text-xs text-slate-400">{{ __('student_life_clubs_teacher_in_charge') }}</p>
                                    <p class="text-xs font-semibold text-slate-700">{{ $club->patron_name ?? '—' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400">{{ __('student_life_clubs_president') }}</p>
                                    <p class="text-xs font-semibold text-slate-700">
                                        {{ $club->president_name ?? '—' }}
                                        @if($club->president_class)
                                            · {{ $club->president_class }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 md:col-span-2">{{ __('student_life_clubs_empty') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- Houses --}}
            <div>
                <h2 class="text-lg font-bold text-slate-900 mb-5">{{ __('student_life_tab_houses') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @forelse($houses as $i => $house)
                        @php $cc = $house->colour_classes; @endphp
                        <div class="group relative bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all"
                             data-reveal="scale" style="--reveal-delay: {{ $i * 80 }}ms">
                            <div class="absolute inset-y-0 left-0 w-1.5 {{ $cc['bg'] }}"></div>
                            <svg viewBox="0 0 24 24" class="absolute -right-3 -top-4 w-28 h-28 {{ $cc['text'] }} opacity-10" fill="currentColor">
                                <path d="M12 1 3 5v7c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5L12 1Zm0 4 5 2.18V12c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5Z"/>
                            </svg>
                            <div class="relative p-6 pl-7">
                                <div class="flex items-start gap-4 mb-5">
                                    <div class="w-16 h-16 flex-shrink-0 flex items-center justify-center rounded-2xl {{ $cc['bg_light'] }} shadow-sm ring-1 ring-inset ring-white/80">
                                        <svg viewBox="0 0 24 24" class="w-10 h-10 {{ $cc['text'] }}" fill="currentColor">
                                            <path d="M12 1 3 5v7c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5L12 1Zm0 4 5 2.18V12c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5Z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-black text-slate-900 text-lg leading-tight">{{ $house->name }}</h3>
                                        @if($house->motto)
                                            <span class="text-xs font-semibold italic {{ $cc['text'] }}">"{{ $house->motto }}"</span>
                                        @endif
                                    </div>
                                </div>
                                @if($house->description)
                                    <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $house->description }}</p>
                                @endif
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                    <div>
                                        <p class="text-xs text-slate-400">{{ __('student_life_houses_master') }}</p>
                                        <p class="text-xs font-semibold text-slate-700">{{ $house->house_master_name ?? '—' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-400">{{ __('student_life_houses_captain') }}</p>
                                        <p class="text-xs font-semibold text-slate-700">
                                            {{ $house->captain_name ?? '—' }}
                                            @if($house->captain_class)
                                                · {{ $house->captain_class }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 col-span-2">{{ __('student_life_houses_empty') }}</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- Prefects tab --}}
        @if($activeTab === 'prefects')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($prefects as $i => $prefect)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 text-center"
                         data-reveal="scale" style="--reveal-delay: {{ ($i % 8) * 55 }}ms">
                        @if($prefect->photo_path)
                            <img src="{{ $prefect->photo_url }}" alt="{{ $prefect->name }}"
                                 class="w-20 h-20 rounded-full mx-auto mb-4 object-cover ring-4 {{ $prefect->role_colour }}">
                        @else
                            <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center font-black text-2xl ring-4 {{ $prefect->role_colour }}">
                                {{ $prefect->initials }}
                            </div>
                        @endif
                        <p class="font-bold text-slate-900 text-sm">{{ $prefect->name }}</p>
                        @php
                        $roleTextClass = match(true) {
                            str_contains($prefect->role_colour, 'rose')    => 'text-[#B21F2D]',
                            str_contains($prefect->role_colour, 'violet')  => 'text-[#002366]',
                            str_contains($prefect->role_colour, 'emerald') => 'text-emerald-600',
                            str_contains($prefect->role_colour, 'sky')     => 'text-[#002366]',
                            str_contains($prefect->role_colour, 'amber')   => 'text-[#B21F2D]',
                            str_contains($prefect->role_colour, 'teal')    => 'text-teal-600',
                            str_contains($prefect->role_colour, 'orange')  => 'text-[#B21F2D]',
                            default                                         => 'text-slate-500',
                        };
                        @endphp
                        <p class="text-xs font-semibold uppercase tracking-wide mt-0.5 mb-1 {{ $roleTextClass }}">{{ $prefect->role }}</p>
                        @if($prefect->class_name)
                            <p class="text-xs text-slate-400 mb-3">{{ $prefect->class_name }}</p>
                        @endif
                        @if($prefect->quote)
                            <p class="text-xs text-slate-500 italic leading-relaxed">"{{ $prefect->quote }}"</p>
                        @endif
                    </div>
                @empty
                    <p class="text-slate-400 md:col-span-2">{{ __('student_life_prefects_empty') }}</p>
                @endforelse
            </div>
        @endif

        {{-- Uniform Bodies tab --}}
        @if($activeTab === 'uniform-bodies')
            @php
            $uniformBg = [
                'rose'    => 'bg-[#B21F2D]/10',    'sky'     => 'bg-[#002366]/10',
                'emerald' => 'bg-emerald-100', 'amber'   => 'bg-[#B21F2D]/10',
                'violet'  => 'bg-[#002366]/10',  'teal'    => 'bg-teal-100',
            ];
            $uniformFg = [
                'rose'    => 'text-[#B21F2D]',  'sky'     => 'text-[#002366]',
                'emerald' => 'text-emerald-700','amber'  => 'text-[#B21F2D]',
                'violet'  => 'text-[#002366]','teal'    => 'text-teal-700',
            ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($uniformBodies as $i => $body)
                    @php
                    $ubg = $uniformBg[$body->colour] ?? 'bg-slate-100';
                    $ufg = $uniformFg[$body->colour] ?? 'text-slate-600';
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-md transition-shadow"
                         data-reveal="scale" style="--reveal-delay: {{ ($i % 3) * 70 }}ms">
                        <div class="flex items-center gap-3 mb-4">
                            @if($body->logo_path)
                                <img src="{{ $body->logo_url }}" alt="{{ $body->name }}" class="w-12 h-12 rounded-xl object-cover flex-shrink-0">
                            @else
                                <div class="w-12 h-12 flex-shrink-0 rounded-xl flex items-center justify-center {{ $ubg }} {{ $ufg }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5Z"/>
                                    </svg>
                                </div>
                            @endif
                            <span class="inline-block text-xs font-bold uppercase tracking-wide px-2.5 py-0.5 rounded-full {{ $ubg }} {{ $ufg }}">
                                {{ $body->group_type }}
                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">{{ $body->name }}</h3>
                        @if($body->meeting_schedule)
                            <p class="text-xs text-[#002366] font-semibold mb-3 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $body->meeting_schedule }}
                            </p>
                        @endif
                        @if($body->description)
                            <p class="text-sm text-slate-600 leading-relaxed mb-4">{{ $body->description }}</p>
                        @endif
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-slate-400">{{ __('student_life_uniform_patron') }}</p>
                                <p class="text-xs font-semibold text-slate-700">{{ $body->patron_name ?? '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-slate-400">{{ __('student_life_uniform_leader') }}</p>
                                <p class="text-xs font-semibold text-slate-700">
                                    {{ $body->leader_name ?? '—' }}
                                    @if($body->leader_class)
                                        · {{ $body->leader_class }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 md:col-span-2">{{ __('student_life_uniform_empty') }}</p>
                @endforelse
            </div>
        @endif

    </div>

</div>

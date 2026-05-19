<div>
    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2" data-reveal="fade">{{ __('about_page_label') }}</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900" data-reveal="left">{{ $profile->school_name }}</h1>
            @if($profile->motto)
                <p class="text-sm text-slate-500 italic mt-1" data-reveal="fade">"{{ $profile->motto }}"</p>
            @endif
        </div>
    </section>

    {{-- Tab nav --}}
    <div class="sticky top-16 z-40 bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-0 overflow-x-auto">
                <button
                    wire:click="switchTab('about')"
                    class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors {{ $activeTab === 'about' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
                >{{ __('about_tab_about') }}</button>
                <button
                    wire:click="switchTab('history')"
                    class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors {{ $activeTab === 'history' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
                >{{ __('about_tab_history') }}</button>
                <button
                    wire:click="switchTab('achievements')"
                    class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors {{ $activeTab === 'achievements' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
                >{{ __('about_tab_achievements') }}</button>
                <button
                    wire:click="switchTab('team')"
                    class="px-5 py-4 text-sm font-semibold whitespace-nowrap border-b-2 transition-colors {{ $activeTab === 'team' ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}"
                >{{ __('about_tab_team') }}</button>
            </div>
        </div>
    </div>

    {{-- Tab content --}}
    <div>

        {{-- ===================== TAB: ABOUT US ===================== --}}
        @if($activeTab === 'about')

            {{-- Mission & Vision --}}
            <section class="py-12 sm:py-16 bg-slate-50 border-b border-slate-200">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-8">
                        <span class="text-xs font-black uppercase tracking-widest text-rose-500">{{ __('about_section_our_purpose') }}</span>
                        <h2 class="text-3xl font-black text-slate-900 mt-1">{{ __('about_section_mission_vision') }}</h2>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                        {{-- Mission --}}
                        <div class="bg-white rounded-2xl border border-rose-100 p-7">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-rose-50 border border-rose-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest text-rose-600">{{ __('about_label_mission') }}</p>
                            </div>
                            <p class="text-slate-700 leading-relaxed">{{ $mission->mission ?? __('about_mission_not_set') }}</p>
                        </div>
                        {{-- Vision --}}
                        <div class="bg-white rounded-2xl border border-sky-100 p-7">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-sky-50 border border-sky-200 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </div>
                                <p class="text-xs font-black uppercase tracking-widest text-sky-600">{{ __('about_label_vision') }}</p>
                            </div>
                            <p class="text-slate-700 leading-relaxed">{{ $mission->vision ?? __('about_vision_not_set') }}</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Principal's Message --}}
            <section class="py-16 sm:py-20 bg-white">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('about_leadership_eyebrow') }}</p>
                        <h2 class="text-3xl font-black text-slate-900">{{ __('about_principals_message') }}</h2>
                        <div class="mt-3 mx-auto w-10 h-1 rounded-full bg-rose-500"></div>
                    </div>
                    @if($profile->principal_name || $profile->principal_message)
                        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-8 sm:p-10">
                            <div class="flex flex-col sm:flex-row gap-8">
                                <div class="flex-shrink-0 flex flex-col items-center gap-3">
                                    <div class="w-60 h-60 rounded-full overflow-hidden ring-4 ring-white shadow-md">
                                        @if($profile->principal_photo_url)
                                            <img src="{{ $profile->principal_photo_url }}" alt="{{ $profile->principal_name }}" class="w-full h-full object-cover object-top">
                                        @else
                                            <div class="w-full h-full bg-slate-200 flex items-center justify-center">
                                                <svg class="w-16 h-16 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-center">
                                        <p class="font-bold text-slate-900">{{ $profile->principal_name }}</p>
                                        <p class="text-xs text-rose-600 font-semibold mt-0.5">{{ $profile->principal_designation ?? __('about_label_principal') }}</p>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="border-l-4 border-rose-500 pl-5 mb-6">
                                        <p class="text-xs font-bold uppercase tracking-widest text-rose-600">{{ __('about_open_letter') }}</p>
                                    </div>
                                    <div class="space-y-4 text-slate-700 leading-relaxed text-sm">
                                        @foreach(array_filter(explode("\n\n", $profile->principal_message ?? '')) as $i => $para)
                                            <p class="{{ $i === 0 ? 'font-medium text-slate-800' : '' }}">{{ $para }}</p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Leadership Team --}}
            @if($leadership->isNotEmpty())
                <section class="py-16 sm:py-20 bg-slate-50">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-12">
                            <p class="text-xs font-bold uppercase tracking-widest text-sky-600 mb-2">{{ __('about_leadership_eyebrow_2') }}</p>
                            <h2 class="text-3xl font-black text-slate-900">{{ __('about_leadership_team') }}</h2>
                            <div class="mt-3 mx-auto w-10 h-1 rounded-full bg-rose-500"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($leadership as $member)
                                @php $isFirst = $loop->first; @endphp
                                <div class="rounded-2xl border {{ $isFirst ? 'border-rose-200 hover:border-rose-400' : 'border-slate-200 hover:border-slate-300' }} bg-white p-6 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 flex flex-col items-center text-center">
                                    <div class="relative mb-4">
                                        <div class="w-24 h-24 rounded-full overflow-hidden ring-4 {{ $isFirst ? 'ring-rose-100' : 'ring-slate-100' }}">
                                            @if($member->photo_url)
                                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover object-top">
                                            @else
                                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-400 text-lg font-bold">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                            @endif
                                        </div>
                                        @if($isFirst)
                                            <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-rose-600 text-white text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full whitespace-nowrap">{{ __('about_label_principal') }}</div>
                                        @endif
                                    </div>
                                    <p class="font-bold text-slate-900 {{ $isFirst ? 'mt-3' : 'mt-1' }}">{{ app()->getLocale() === 'dv' && $member->name_dv ? $member->name_dv : $member->name }}</p>
                                    <p class="text-xs font-semibold uppercase tracking-wide mt-0.5 mb-3 {{ $isFirst ? 'text-rose-600' : 'text-sky-600' }}">{{ $member->role }}</p>
                                    @if($member->bio)
                                        <p class="text-sm text-slate-600 leading-relaxed line-clamp-3">{{ $member->bio }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

        @endif

        {{-- ===================== TAB: HISTORY ===================== --}}
        @if($activeTab === 'history')

            {{-- School overview --}}
            <section class="py-16 sm:py-20 bg-white border-b border-slate-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                        <div class="space-y-6">
                            @if($profile->motto)
                                <div class="pb-5 border-b border-slate-100">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ __('about_history_label_motto') }}</p>
                                    <p class="font-semibold text-slate-900 italic">"{{ $profile->motto }}"</p>
                                </div>
                            @endif
                            @php
                                $location = collect([
                                    $profile->getTranslation('island', app()->getLocale(), false),
                                    $profile->getTranslation('atoll', app()->getLocale(), false),
                                ])->filter()->join(', ');
                            @endphp
                            @if($location)
                                <div class="pb-5 border-b border-slate-100">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">{{ __('about_history_label_location') }}</p>
                                    <p class="font-medium text-slate-700">{{ $location }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="lg:col-span-2">
                            @if($profile->short_description)
                                <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-3">{{ __('about_history_who_we_are') }}</p>
                                @foreach(array_filter(explode("\n\n", $profile->short_description)) as $i => $para)
                                    <p class="mb-4 leading-relaxed {{ $i === 0 ? 'text-lg font-medium text-slate-800' : 'text-slate-600' }}">{{ $para }}</p>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            {{-- History Timeline --}}
            <section class="py-16 sm:py-24 bg-white">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="mb-14 pb-10 border-b border-slate-100">
                        <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-3">{{ __('about_history_eyebrow') }}</p>
                        <h2 class="text-4xl sm:text-5xl font-black text-slate-900 mb-5">{{ __('about_history_heading') }}</h2>
                    </div>
                    @if($historySections->isNotEmpty())
                        @php
                            $timelineColors = [
                                0 => ['ring' => 'ring-rose-200',    'dot' => 'bg-rose-500',    'badge' => 'bg-rose-50 border-rose-200 text-rose-700'],
                                1 => ['ring' => 'ring-amber-200',   'dot' => 'bg-amber-500',   'badge' => 'bg-amber-50 border-amber-200 text-amber-700'],
                                2 => ['ring' => 'ring-sky-200',     'dot' => 'bg-sky-500',     'badge' => 'bg-sky-50 border-sky-200 text-sky-700'],
                                3 => ['ring' => 'ring-emerald-200', 'dot' => 'bg-emerald-500', 'badge' => 'bg-emerald-50 border-emerald-200 text-emerald-700'],
                            ];
                        @endphp
                        <div class="relative">
                            <div class="absolute left-5 top-0 bottom-0 w-px bg-gradient-to-b from-rose-300 via-slate-200 to-slate-100"></div>
                            <div class="space-y-10">
                                @foreach($historySections as $section)
                                    @php
                                        $colorSet = $timelineColors[$loop->index % 4];
                                    @endphp
                                    <div class="relative flex gap-6 sm:gap-10">
                                        <div class="flex-shrink-0 pt-0.5 z-10">
                                            <div class="w-10 h-10 rounded-full bg-white ring-4 {{ $colorSet['ring'] }} flex items-center justify-center shadow-sm">
                                                <div class="w-3.5 h-3.5 rounded-full {{ $colorSet['dot'] }}"></div>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0 pb-4">
                                            @if($section->year_label)
                                                <span class="inline-block text-[11px] font-black uppercase tracking-wider border px-2.5 py-0.5 rounded-full mb-3 {{ $colorSet['badge'] }}">{{ $section->year_label }}</span>
                                            @endif
                                            <h3 class="text-lg sm:text-xl font-bold text-slate-900 mb-4 leading-snug">{{ $section->title }}</h3>
                                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 sm:p-7 space-y-3">
                                                @foreach(array_filter(explode("\n\n", $section->body)) as $para)
                                                    <p class="text-slate-600 leading-relaxed">{{ $para }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-slate-500">{{ __('about_history_empty') }}</p>
                    @endif
                </div>
            </section>

            {{-- Founding Members --}}
            @if($foundingMembers->isNotEmpty())
                <section class="py-16 sm:py-20 bg-stone-950 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-[0.03]" style="background-image:repeating-linear-gradient(0deg,#d97706 0,#d97706 1px,transparent 0,transparent 32px),repeating-linear-gradient(90deg,#d97706 0,#d97706 1px,transparent 0,transparent 32px)"></div>
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="text-center mb-14">
                            <div class="inline-flex items-center gap-3 mb-4">
                                <span class="h-px w-10 bg-amber-600/50"></span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-amber-500 border border-amber-700/30 px-3 py-1 rounded-sm">{{ __('about_founding_eyebrow') }}</span>
                                <span class="h-px w-10 bg-amber-600/50"></span>
                            </div>
                            <h2 class="text-3xl font-black text-white">{{ __('about_founding_heading') }}</h2>
                            <p class="mt-3 text-amber-200/50 text-sm max-w-md mx-auto">{{ __('about_founding_subtext') }}</p>
                        </div>
                        <div class="grid justify-center gap-5 [grid-template-columns:repeat(auto-fit,minmax(8.5rem,10rem))]">
                            @foreach($foundingMembers as $member)
                                <div class="group text-center">
                                    <div class="relative aspect-[3/4] overflow-hidden rounded-lg mb-3 ring-1 ring-amber-700/20">
                                        @if($member->photo_path)
                                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover object-top grayscale sepia brightness-75 group-hover:grayscale-0 group-hover:sepia-0 group-hover:brightness-90 transition-all duration-700">
                                        @else
                                            <div class="w-full h-full bg-stone-800 flex items-center justify-center text-amber-400 text-3xl font-black">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                                        @endif
                                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/20 to-transparent"></div>
                                    </div>
                                    <p class="font-bold text-white text-sm leading-tight">{{ app()->getLocale() === 'dv' && $member->name_dv ? $member->name_dv : $member->name }}</p>
                                    <p class="text-amber-400 text-[11px] font-semibold uppercase tracking-wide mt-0.5">{{ $member->subject }}</p>
                                    @if($member->tribute)
                                        <p class="text-stone-400 text-xs mt-1.5 leading-relaxed line-clamp-3">{{ $member->tribute }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

        @endif

        {{-- ===================== TAB: ACHIEVEMENTS ===================== --}}
        @if($activeTab === 'achievements')
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

                {{-- Header --}}
                <div class="mb-10">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">{{ __('about_achievements_eyebrow') }}</p>
                    <h2 class="text-3xl sm:text-4xl font-black text-slate-900">{{ __('about_achievements_heading') }}</h2>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-4 mb-10">
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-center">
                        <p class="text-3xl font-black text-slate-900">{{ $totalCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mt-1">{{ __('about_achievements_stat_total') }}</p>
                    </div>
                    <div class="bg-sky-50 border border-sky-200 rounded-2xl p-5 text-center">
                        <p class="text-3xl font-black text-sky-700">{{ $studentCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-sky-600 mt-1">{{ __('about_achievements_stat_students') }}</p>
                    </div>
                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 text-center">
                        <p class="text-3xl font-black text-rose-700">{{ $schoolCount }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 mt-1">{{ __('about_achievements_stat_school') }}</p>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <div class="flex gap-2 flex-wrap">
                        @php
                            $categoryBtns = [
                                'all'      => ['label' => 'about_achievements_filter_all',      'active' => 'bg-slate-900 border-slate-900 text-white'],
                                'students' => ['label' => 'about_achievements_filter_students', 'active' => 'bg-sky-600 border-sky-600 text-white'],
                                'staff'    => ['label' => 'about_achievements_filter_staff',    'active' => 'bg-violet-600 border-violet-600 text-white'],
                                'school'   => ['label' => 'about_achievements_filter_school',   'active' => 'bg-rose-600 border-rose-600 text-white'],
                            ];
                        @endphp
                        @foreach($categoryBtns as $catKey => $catInfo)
                            <button
                                wire:click="switchCategory('{{ $catKey }}')"
                                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors {{ $activeCategory === $catKey ? $catInfo['active'] : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}"
                            >{{ __($catInfo['label']) }}</button>
                        @endforeach
                    </div>
                    <select
                        wire:model.live="activeYear"
                        class="sm:ml-auto px-4 py-2 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 bg-white focus:outline-none focus:ring-2 focus:ring-rose-400"
                    >
                        <option value="all">{{ __('common_all_years') }}</option>
                        @foreach($achievementYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Achievement cards --}}
                @if($achievements->isNotEmpty())
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @foreach($achievements as $achievement)
                            @php
                                $badgeCls = match($achievement->category) {
                                    'school'   => 'bg-rose-100 text-rose-700',
                                    'students' => 'bg-sky-100 text-sky-700',
                                    'staff'    => 'bg-violet-100 text-violet-700',
                                    default    => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <div class="bg-white rounded-2xl border border-slate-200 hover:border-slate-300 hover:shadow-md transition-all duration-200 p-6 flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    @if($achievement->photo_url)
                                        <div class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-slate-100 flex-shrink-0">
                                            <img src="{{ $achievement->photo_url }}" alt="{{ $achievement->person_name }}" class="w-full h-full object-cover object-top">
                                        </div>
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-10 h-10 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0 pt-1">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <span class="text-[11px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full {{ $badgeCls }}">{{ __($categoryBtns[$achievement->category]['label'] ?? $achievement->category) }}</span>
                                            <span class="text-xs font-semibold text-slate-400 flex-shrink-0">{{ $achievement->year }}</span>
                                        </div>
                                        @if($achievement->person_name || $achievement->person_name_dv)
                                            <p class="text-sm font-semibold text-slate-700 mt-1">{{ app()->getLocale() === 'dv' && $achievement->person_name_dv ? $achievement->person_name_dv : $achievement->person_name }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-slate-900 text-base leading-snug mb-2">{{ $achievement->title }}</h3>
                                    @if($achievement->description)
                                        <p class="text-sm text-slate-600 leading-relaxed">{{ $achievement->description }}</p>
                                    @endif
                                </div>
                                @if($achievement->award || $achievement->event_name)
                                <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                                    @if($achievement->award)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-black text-slate-900">
                                            <svg class="w-3.5 h-3.5 text-amber-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                            {{ $achievement->award }}
                                        </span>
                                    @endif
                                    @if($achievement->award && $achievement->event_name)
                                        <span class="text-slate-300">·</span>
                                    @endif
                                    @if($achievement->event_name)
                                        <span class="text-xs text-slate-500">{{ $achievement->event_name }}</span>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </div>
                        <p class="text-slate-500 font-medium">{{ __('about_achievements_empty') }}</p>
                    </div>
                @endif

            </div>
        @endif

        {{-- ===================== TAB: TEAM ===================== --}}
        @if($activeTab === 'team')
        <div class="py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                {{-- Senior Management --}}
                @php $seniorStaff = $staff->get('senior_management', collect()); @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden" data-reveal="scale">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <p class="text-xs font-bold uppercase tracking-widest text-rose-600">{{ __('about_team_leadership_eyebrow') }}</p>
                        <h2 class="text-xl font-black text-slate-900 mt-0.5">{{ __('about_team_senior_management') }}</h2>
                    </div>
                    <div class="p-6">
                        @if($seniorStaff->isEmpty())
                            <p class="text-sm text-slate-400">{{ __('about_team_no_members') }}</p>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach($seniorStaff as $member)
                                    @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'rose'])
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Academic Section --}}
                @php
                    $academicStaff = $staff->get('academic', collect())->groupBy('sub_section');
                    $academicSubSections = [
                        'leading_teachers' => 'Leading Teachers',
                        'teachers'         => 'Teachers',
                        'academic_support' => 'Academic Support Staff',
                        'laboratory'       => 'Laboratory',
                        'library'          => 'Library',
                    ];
                @endphp
                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden" data-reveal="scale" style="--reveal-delay: 80ms">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <div class="text-left">
                            <p class="text-xs font-bold uppercase tracking-widest text-rose-600">{{ __('about_team_section_eyebrow') }}</p>
                            <h2 class="text-xl font-black text-slate-900 mt-0.5">{{ __('about_team_academic_section') }}</h2>
                            <p class="text-xs text-slate-400 mt-0.5">{{ __('about_team_academic_lead') }}</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 space-y-8">
                        @forelse($academicSubSections as $key => $label)
                            @php $group = $academicStaff->get($key, collect()); @endphp
                            @if($group->isNotEmpty())
                                @php $isNested = in_array($key, ['laboratory', 'library']); @endphp
                                <div class="{{ $isNested ? 'ml-6 pl-4 border-l-2 border-slate-100' : '' }}">
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{{ $label }}</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($group as $member)
                                            @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'sky'])
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-slate-400">{{ __('about_team_no_academic_staff') }}</p>
                        @endforelse
                    </div>
                </div>

                {{-- Administrative Section --}}
                @php
                    $adminStaff = $staff->get('administrative', collect())->groupBy('sub_section');
                    $adminSubSections = [
                        'hr'      => 'Human Resources',
                        'budget'  => 'Budget',
                        'it'      => 'IT',
                        'printer' => 'Printing',
                    ];
                @endphp
                <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden" data-reveal="scale" style="--reveal-delay: 160ms">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-6 py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <div class="text-left">
                            <p class="text-xs font-bold uppercase tracking-widest text-rose-600">{{ __('about_team_section_eyebrow') }}</p>
                            <h2 class="text-xl font-black text-slate-900 mt-0.5">{{ __('about_team_admin_section') }}</h2>
                            <p class="text-xs text-slate-400 mt-0.5">{{ __('about_team_admin_lead') }}</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-300 transition-transform duration-200 flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="p-6 space-y-8">
                        @forelse($adminSubSections as $key => $label)
                            @php $group = $adminStaff->get($key, collect()); @endphp
                            @if($group->isNotEmpty())
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">{{ $label }}</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                                        @foreach($group as $member)
                                            @include('livewire.website.partials.staff-card', ['member' => $member, 'accentColor' => 'amber'])
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-slate-400">{{ __('about_team_no_admin_staff') }}</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
        @endif

    </div>
</div>

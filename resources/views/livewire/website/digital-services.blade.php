<div>

    {{-- Page header --}}
    <div class="bg-white border-b border-slate-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal="fade">
            <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('digital_services_page_label') }}</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">{{ __('digital_services_heading') }}</h1>
        </div>
    </div>

    {{-- Sticky tab bar --}}
    <div class="bg-white border-b border-slate-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-0 overflow-x-auto">
                @foreach([['downloads','digital_services_tab_downloads'],['resources','digital_services_tab_resources'],['calendar','digital_services_tab_calendar']] as [$id,$labelKey])
                    <button wire:click="setTab('{{ $id }}')"
                            class="flex-shrink-0 px-5 py-4 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap
                                   {{ $activeTab === $id ? 'border-[#002366] text-[#002366]' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
                        {{ __($labelKey) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @php
            $docCategoryLabels = [
                'Forms & Applications' => 'digital_services_category_forms_applications',
                'Policies & Handbooks' => 'digital_services_category_policies_handbooks',
                'Timetables & Schedules' => 'digital_services_category_timetables_schedules',
                'Academic Resources' => 'digital_services_category_academic_resources',
            ];
            $audienceLabels = [
                'All' => 'digital_services_audience_all',
                'Students' => 'digital_services_audience_students',
                'Parents' => 'digital_services_audience_parents',
                'Staff' => 'digital_services_audience_staff',
            ];
            $localizedDate = fn ($date) => $date->format('d') . ' ' . __('common_month_short_' . $date->month) . ' ' . $date->format('Y');
            $localizedMonthYear = fn ($date) => __('common_month_' . $date->month) . ' ' . $date->format('Y');
        @endphp

        {{-- Downloads tab --}}
        @if($activeTab === 'downloads')
            <div>
                {{-- Search --}}
                <div class="relative max-w-md mb-4">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="{{ __('digital_services_search_placeholder') }}"
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#002366] text-sm bg-white">
                </div>

                {{-- Filters row: category pills + selects + clear --}}
                <div class="flex flex-wrap items-center gap-2 mb-4" data-reveal="fade">
                    <button wire:click="setCategory('All')"
                            class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                   {{ $activeCategory === 'All' ? 'bg-[#002366] border-[#002366] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                        {{ __('common_all') }}
                    </button>
                    @foreach($docCategories as $cat)
                        <button wire:click="setCategory('{{ $cat }}')"
                                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                       {{ $activeCategory === $cat ? 'bg-[#002366] border-[#002366] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                            {{ __($docCategoryLabels[$cat] ?? $cat) }}
                        </button>
                    @endforeach
                    <div class="ml-auto flex items-center gap-2">
                        <select wire:model.live="activeMonth"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#002366] font-medium">
                            <option value="All">{{ __('common_all_months') }}</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ $month }}">{{ __('common_month_' . $month) }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="activeYear"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#002366] font-medium">
                            <option value="All">{{ __('common_all_years') }}</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <button wire:click="clearFilters"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                            {{ __('common_clear') }}
                        </button>
                    </div>
                </div>

                {{-- Documents list --}}
                @php
                $ftColors = [
                    'PDF'  => 'bg-[#002366]/10 text-[#002366]',
                    'DOCX' => 'bg-sky-100 text-sky-700',
                    'XLS'  => 'bg-emerald-100 text-emerald-700',
                    'XLSX' => 'bg-emerald-100 text-emerald-700',
                ];
                $audColors = [
                    'Parents'  => 'bg-violet-100 text-violet-700',
                    'Students' => 'bg-amber-100 text-amber-700',
                    'Staff'    => 'bg-slate-100 text-slate-600',
                    'All'      => 'bg-zinc-100 text-zinc-600',
                ];
                @endphp

                @if($documents->isEmpty())
                    <div class="text-center py-12 text-slate-400">
                        <p class="font-semibold">{{ __('digital_services_no_documents') }}</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($documents as $i => $doc)
                            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4 hover:shadow-sm transition-shadow"
                                 data-reveal="fade" style="--reveal-delay: {{ ($i % 10) * 40 }}ms">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 text-sm truncate">{{ $doc->title }}</p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $ftColors[$doc->file_type] ?? 'bg-slate-100 text-slate-600' }}">{{ $doc->file_type }}</span>
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $audColors[$doc->audience] ?? 'bg-slate-100 text-slate-600' }}">{{ __($audienceLabels[$doc->audience] ?? $doc->audience) }}</span>
                                        @if($doc->file_size)
                                            <span class="text-xs text-slate-400">{{ $doc->file_size }}</span>
                                        @endif
                                        <span class="text-xs text-slate-400">{{ $localizedDate($doc->published_at) }}</span>
                                    </div>
                                </div>
                                @if($doc->file_path)
                                    <a href="{{ $doc->download_url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex-shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold text-[#002366] hover:text-[#002366] bg-[#002366]/5 hover:bg-[#002366]/10 px-3 py-2 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        {{ __('digital_services_download') }}
                                    </a>
                                @else
                                    <span class="flex-shrink-0 text-xs text-slate-300 px-3 py-2">{{ __('digital_services_no_file') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($documents->hasPages())
                    <div class="mt-4">{{ $documents->links() }}</div>
                @endif
            </div>
        @endif

        {{-- Resources tab --}}
        @if($activeTab === 'resources')
            <div>
                {{-- Audience filter --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach(['All','Students','Parents','Staff'] as $aud)
                        <button wire:click="setAudience('{{ $aud }}')"
                                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                       {{ $activeAudience === $aud ? 'bg-[#002366] border-[#002366] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                            {{ __($audienceLabels[$aud] ?? $aud) }}
                        </button>
                    @endforeach
                </div>

                @if($resources->isEmpty())
                    <div class="text-center py-16 text-slate-400">
                        <p class="font-semibold">{{ __('digital_services_no_resources') }}</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($resources as $i => $resource)
                            <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                               class="group bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md hover:border-[#002366]/20 transition-all block"
                               data-reveal="scale" style="--reveal-delay: {{ ($i % 6) * 60 }}ms">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $resource->icon_color_classes }}">
                                        {{-- Icon rendered via icon name stored in DB --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            @switch($resource->icon)
                                                @case('BookOpen')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                    @break
                                                @case('BarChart2')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 20V10M12 20V4M6 20v-6"/>
                                                    @break
                                                @case('Users')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                    @break
                                                @case('CreditCard')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                    @break
                                                @case('Library')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                                                    @break
                                                @case('Briefcase')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    @break
                                                @case('Mail')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    @break
                                                @case('ClipboardList')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    @break
                                                @case('Bell')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                    @break
                                                @default
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            @endswitch
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <p class="font-bold text-slate-900 text-sm">{{ $resource->title }}</p>
                                            <span class="text-slate-400 group-hover:text-[#002366] transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">{{ __($audienceLabels[$resource->audience] ?? $resource->audience) }}</span>
                                    </div>
                                </div>
                                @if($resource->description)
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $resource->description }}</p>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Academic Calendar tab --}}
        @if($activeTab === 'calendar')
            @php
                $calTypeColors = [
                    'holiday' => 'bg-[#002366]/10 text-[#002366]',
                    'term'    => 'bg-sky-100 text-sky-700',
                    'event'   => 'bg-emerald-100 text-emerald-700',
                    'exam'    => 'bg-amber-100 text-amber-700',
                ];
            @endphp

            {{-- Calendar selector + tentative badge --}}
            <div class="flex flex-wrap items-center gap-3 mb-4">
                @if($allCalendars->count() > 1)
                    <select wire:model.live="activeCalendarId"
                            class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#002366]">
                        @foreach($allCalendars as $cal)
                            <option value="{{ $cal->id }}">{{ $cal->title }}</option>
                        @endforeach
                    </select>
                @endif

                @if($allCalendarEntries->where('is_tentative', true)->isNotEmpty())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        {{ __('calendar_tentative_badge') }}
                    </span>
                @endif
            </div>

            {{-- Stats row --}}
            @php
                $statCards = [
                    [
                        'label' => __('calendar_stat_teaching_days'),
                        'value' => $currentCalendar?->stat_teaching_days,
                        'color' => 'text-sky-600',
                        'bg'    => 'bg-sky-50',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>',
                    ],
                    [
                        'label' => __('calendar_stat_school_exams'),
                        'value' => $currentCalendar?->stat_exam_days,
                        'color' => 'text-amber-600',
                        'bg'    => 'bg-amber-50',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>',
                    ],
                    [
                        'label' => __('calendar_stat_report_prep'),
                        'value' => $currentCalendar?->stat_report_prep_days,
                        'color' => 'text-violet-600',
                        'bg'    => 'bg-violet-50',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>',
                    ],
                    [
                        'label' => __('calendar_stat_teacher_pd'),
                        'value' => $currentCalendar?->stat_teacher_pd_days,
                        'color' => 'text-emerald-600',
                        'bg'    => 'bg-emerald-50',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>',
                    ],
                    [
                        'label' => __('calendar_stat_non_teaching'),
                        'value' => $currentCalendar?->stat_non_teaching_days,
                        'color' => 'text-slate-500',
                        'bg'    => 'bg-slate-50',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z"/></svg>',
                    ],
                    [
                        'label' => __('calendar_stat_total_days'),
                        'value' => $currentCalendar?->stat_total_days,
                        'color' => 'text-[#002366]',
                        'bg'    => 'bg-[#002366]/5',
                        'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5"/></svg>',
                    ],
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                @foreach($statCards as $i => $stat)
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center"
                         data-reveal="scale" style="--reveal-delay: {{ $i * 55 }}ms">
                        <div class="flex items-center justify-center w-9 h-9 rounded-full {{ $stat['bg'] }} {{ $stat['color'] }} mx-auto mb-2">
                            {!! $stat['icon'] !!}
                        </div>
                        <p class="text-2xl sm:text-3xl font-black text-slate-900 leading-none">
                            {{ $stat['value'] !== null ? rtrim(rtrim(number_format((float)$stat['value'], 1), '0'), '.') : '—' }}
                        </p>
                        <p class="text-xs font-semibold text-slate-400 mt-1.5 leading-tight">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Main area: calendar grid + side panel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                {{-- Monthly calendar grid (2/3 on lg) --}}
                <div class="lg:col-span-2" data-reveal="fade">

                    {{-- Month navigation --}}
                    <div class="bg-white rounded-xl border border-slate-200 px-4 py-3 flex items-center justify-between mb-3">
                        <button wire:click="prevMonth" @disabled($calendarMonth <= 0)
                                class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h3 class="text-base font-black text-slate-900">{{ $localizedMonthYear($currentMonthCarbon) }}</h3>
                        <button wire:click="nextMonth" @disabled($calendarMonth >= 12)
                                class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Calendar card --}}
                    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">

                        {{-- Weekday headers --}}
                        <div class="grid grid-cols-7 border-b border-slate-200">
                            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayLabel)
                                <div class="text-center text-[10px] font-bold text-slate-400 uppercase py-2.5">{{ $dayLabel }}</div>
                            @endforeach
                        </div>

                        {{-- Day grid --}}
                        @php
                            $gridFirstDay  = (int) $currentMonthCarbon->copy()->startOfMonth()->dayOfWeek;
                            $gridDaysCount = (int) $currentMonthCarbon->daysInMonth;
                            $gridCells     = (int) ceil(($gridFirstDay + $gridDaysCount) / 7) * 7;
                            $todayStr      = now()->format('Y-m-d');
                        @endphp

                        <div class="grid grid-cols-7 divide-x divide-y divide-slate-100">
                            @for($cell = 0; $cell < $gridCells; $cell++)
                                @php
                                    $dayNum    = $cell - $gridFirstDay + 1;
                                    $isValid   = $dayNum >= 1 && $dayNum <= $gridDaysCount;
                                    $dateStr   = $isValid ? $currentMonthCarbon->copy()->day($dayNum)->format('Y-m-d') : null;
                                    $dayEvents = ($dateStr && isset($entriesByDate[$dateStr])) ? $entriesByDate[$dateStr] : [];
                                    $isToday   = $dateStr === $todayStr;
                                    $isWeekend = in_array($cell % 7, [0, 6]);
                                @endphp
                                <div class="min-h-[72px] sm:min-h-[84px] p-1 sm:p-1.5 {{ !$isValid || $isWeekend ? 'bg-slate-50' : 'bg-white' }}">
                                    @if($isValid)
                                        <div class="flex items-center justify-center w-6 h-6 rounded-full mb-1 mx-auto text-xs
                                                    {{ $isToday ? 'bg-[#002366] text-white font-black' : 'text-slate-600 font-semibold' }}">
                                            {{ $dayNum }}
                                        </div>
                                        <div class="space-y-px">
                                            @foreach(array_slice($dayEvents, 0, 2) as $ev)
                                                <div class="text-[8px] sm:text-[9px] font-semibold px-1 py-px rounded truncate
                                                            {{ $calTypeColors[$ev->type] ?? 'bg-slate-100 text-slate-600' }}">
                                                    @if($ev->is_tentative)<span class="opacity-60">~</span>@endif{{ $ev->title }}
                                                </div>
                                            @endforeach
                                            @if(count($dayEvents) > 2)
                                                <div class="text-[8px] text-slate-400 px-1">+{{ count($dayEvents) - 2 }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="flex flex-wrap gap-3 mt-3">
                        @foreach(['holiday' => 'calendar_legend_holiday', 'term' => 'calendar_legend_academic', 'exam' => 'calendar_legend_exam', 'event' => 'calendar_legend_event'] as $type => $labelKey)
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm {{ $calTypeColors[$type] }}"></span>
                                <span class="text-xs text-slate-500 font-medium">{{ __($labelKey) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Side panel (1/3 on lg) --}}
                <div class="space-y-3" data-reveal="fade" style="--reveal-delay: 120ms">

                    {{-- Current month entries list --}}
                    @php
                        $monthStart = $currentMonthCarbon->copy()->startOfMonth()->format('Y-m-d');
                        $monthEnd   = $currentMonthCarbon->copy()->endOfMonth()->format('Y-m-d');
                        $monthEntries = $allCalendarEntries->filter(
                            fn($e) => $e->date->format('Y-m-d') >= $monthStart && $e->date->format('Y-m-d') <= $monthEnd
                        )->values();
                    @endphp
                    @if($monthEntries->isNotEmpty())
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2 px-0.5">
                                {{ __('calendar_month_events_heading', ['month' => __('common_month_' . $currentMonthCarbon->month)]) }}
                            </p>
                            <div class="space-y-2">
                                @foreach($monthEntries as $me)
                                    <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-start gap-3">
                                        <div class="flex-shrink-0 text-center w-9 pt-0.5">
                                            <p class="text-lg font-black text-[#002366] leading-none">{{ $me->date->format('d') }}</p>
                                            <p class="text-[10px] text-slate-400 uppercase font-semibold">{{ __('common_month_short_' . $me->date->month) }}</p>
                                            <p class="text-[10px] text-slate-300 font-semibold">{{ $me->date->format('Y') }}</p>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                                                <p class="text-xs font-semibold text-slate-900">{{ $me->title }}</p>
                                                <span class="text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded {{ $calTypeColors[$me->type] ?? 'bg-slate-100 text-slate-600' }}">
                                                    {{ $me->type }}
                                                </span>
                                                @if($me->is_tentative)
                                                    <span class="text-[9px] font-bold uppercase tracking-wide px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">{{ __('calendar_tentative_keyword') }}</span>
                                                @endif
                                            </div>
                                            @if($me->end_date)
                                                <p class="text-[10px] text-slate-400 mb-0.5">{{ __('calendar_until') }} {{ $localizedDate($me->end_date) }}</p>
                                            @endif
                                            @if($me->description)
                                                <p class="text-[10px] text-slate-500 leading-relaxed">{{ $me->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Tentative notice (only if calendar has tentative entries) --}}
                    @if($allCalendarEntries->where('is_tentative', true)->isNotEmpty())
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                                </svg>
                                <p class="text-sm font-bold text-amber-800">{{ __('calendar_tentative_heading') }}</p>
                            </div>
                            <p class="text-xs text-amber-700 leading-relaxed">
                                {{ __('calendar_tentative_body') }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Term Breakdown --}}
            @if($currentCalendar && ($currentCalendar->term1_total_days !== null || $currentCalendar->term2_total_days !== null))
            @php
                $termRows = [
                    ['label' => 'Term 1', 'dates' => $currentCalendar->term1_dates, 'total' => $currentCalendar->term1_total_days, 'teaching' => $currentCalendar->term1_teaching_days, 'exam' => $currentCalendar->term1_exam_days],
                    ['label' => 'Term 2', 'dates' => $currentCalendar->term2_dates, 'total' => $currentCalendar->term2_total_days, 'teaching' => $currentCalendar->term2_teaching_days, 'exam' => $currentCalendar->term2_exam_days],
                ];
                $fmt = fn($v) => $v !== null ? rtrim(rtrim(number_format((float)$v, 1), '0'), '.') : '—';
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                @foreach($termRows as $i => $t)
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 hover:shadow-md transition-shadow"
                         data-reveal="scale" style="--reveal-delay: {{ $i * 80 }}ms">
                        <div class="flex items-start justify-between gap-2 mb-4">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-1">{{ $t['label'] }}</p>
                                @if($t['dates'])
                                    <p class="text-xs font-semibold text-slate-500">{{ $t['dates'] }}</p>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-3xl font-black text-slate-900 leading-none">{{ $fmt($t['total']) }}</p>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mt-0.5">{{ __('calendar_term_total_days') }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-sky-50 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $fmt($t['teaching']) }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide leading-none">{{ __('calendar_term_teaching') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $fmt($t['exam']) }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide leading-none">{{ __('calendar_term_exam_days') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif

            {{-- International Exam Series --}}
            @if($currentCalendar && !empty($currentCalendar->exam_series))
            @php
                $seriesColors = [
                    ['bg' => 'bg-sky-50',     'border' => 'border-sky-200',     'text' => 'text-sky-700',     'badge' => 'bg-sky-100 text-sky-700'],
                    ['bg' => 'bg-amber-50',   'border' => 'border-amber-200',   'text' => 'text-amber-700',   'badge' => 'bg-amber-100 text-amber-700'],
                    ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-700'],
                ];
            @endphp
            <div class="mt-4 bg-white rounded-2xl border border-slate-200 p-6" data-reveal="fade">
                <div class="mb-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-1">{{ __('calendar_exams_eyebrow') }}</p>
                    <h3 class="text-lg font-black text-slate-900">{{ __('calendar_exams_heading') }}</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($currentCalendar->exam_series as $i => $series)
                        @php $sc = $seriesColors[$i % 3]; @endphp
                        <div class="rounded-xl border {{ $sc['border'] }} {{ $sc['bg'] }} p-4"
                             data-reveal="scale" style="--reveal-delay: {{ $i * 70 }}ms">
                            <span class="inline-block text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full mb-3 {{ $sc['badge'] }}">
                                {{ $series['sub'] ?? '' }}
                            </span>
                            <p class="font-bold text-slate-900 text-sm leading-snug mb-1.5">{{ $series['title'] ?? '' }}</p>
                            <p class="text-xs font-semibold {{ $sc['text'] }}">{{ $series['period'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 pt-4 border-t border-slate-100 flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-slate-400">{{ __('calendar_exams_dpe_note') }}</p>
                </div>
            </div>
            @endif

        @endif

    </div>

</div>

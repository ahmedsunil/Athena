<div>

    {{-- Page header --}}
    <div class="bg-white border-b border-slate-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">Online Hub</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Digital Services</h1>
        </div>
    </div>

    {{-- Sticky tab bar --}}
    <div class="bg-white border-b border-slate-200 sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-0 overflow-x-auto">
                @foreach([['downloads','Downloads'],['resources','Resources'],['calendar','Academic Calendar']] as [$id,$label])
                    <button wire:click="setTab('{{ $id }}')"
                            class="flex-shrink-0 px-5 py-4 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap
                                   {{ $activeTab === $id ? 'border-rose-600 text-rose-600' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

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
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search documents..."
                           class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 text-sm bg-white">
                </div>

                {{-- Filters row: category pills + selects + clear --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <button wire:click="setCategory('All')"
                            class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                   {{ $activeCategory === 'All' ? 'bg-rose-600 border-rose-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                        All
                    </button>
                    @foreach($docCategories as $cat)
                        <button wire:click="setCategory('{{ $cat }}')"
                                class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                                       {{ $activeCategory === $cat ? 'bg-rose-600 border-rose-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                    <div class="ml-auto flex items-center gap-2">
                        <select wire:model.live="activeMonth"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-500 font-medium">
                            <option value="All">All Months</option>
                            @foreach(['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $month)
                                <option value="{{ $i + 1 }}">{{ $month }}</option>
                            @endforeach
                        </select>
                        <select wire:model.live="activeYear"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-rose-500 font-medium">
                            <option value="All">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        <button wire:click="clearFilters"
                                class="px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-colors">
                            Clear
                        </button>
                    </div>
                </div>

                {{-- Documents list --}}
                @php
                $ftColors = [
                    'PDF'  => 'bg-rose-100 text-rose-700',
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
                        <p class="font-semibold">No documents found</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($documents as $doc)
                            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center gap-4 hover:shadow-sm transition-shadow">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 text-sm truncate">{{ $doc->title }}</p>
                                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $ftColors[$doc->file_type] ?? 'bg-slate-100 text-slate-600' }}">{{ $doc->file_type }}</span>
                                        <span class="text-[10px] font-bold uppercase px-1.5 py-0.5 rounded {{ $audColors[$doc->audience] ?? 'bg-slate-100 text-slate-600' }}">{{ $doc->audience }}</span>
                                        @if($doc->file_size)
                                            <span class="text-xs text-slate-400">{{ $doc->file_size }}</span>
                                        @endif
                                        <span class="text-xs text-slate-400">{{ $doc->published_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                                @if($doc->file_path)
                                    <a href="{{ $doc->download_url }}" target="_blank" rel="noopener noreferrer"
                                       class="flex-shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 px-3 py-2 rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    <span class="flex-shrink-0 text-xs text-slate-300 px-3 py-2">No file</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
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
                                       {{ $activeAudience === $aud ? 'bg-rose-600 border-rose-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                            {{ $aud }}
                        </button>
                    @endforeach
                </div>

                @if($resources->isEmpty())
                    <div class="text-center py-16 text-slate-400">
                        <p class="font-semibold">No resources found</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($resources as $resource)
                            <a href="{{ $resource->url }}" target="_blank" rel="noopener noreferrer"
                               class="group bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md hover:border-rose-200 transition-all block">
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
                                            <span class="text-slate-400 group-hover:text-rose-500 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                            </span>
                                        </div>
                                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">{{ $resource->audience }}</span>
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
                    'holiday' => 'bg-red-100 text-red-700',
                    'term'    => 'bg-blue-100 text-blue-700',
                    'event'   => 'bg-emerald-100 text-emerald-700',
                    'exam'    => 'bg-amber-100 text-amber-700',
                ];
            @endphp

            {{-- Header --}}
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">Ministry of Education</p>
                        <div class="flex items-center gap-3 flex-wrap">
                            <h2 class="text-2xl font-black text-slate-900">
                                Academic Calendar {{ $currentCalendar?->year ?? date('Y') }}
                            </h2>
                            @if($currentCalendar && str_contains(strtolower($currentCalendar->description ?? ''), 'tentative'))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    Tentative
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($allCalendars->count() > 1)
                            @foreach($allCalendars as $cal)
                                <button wire:click="setCalendar({{ $cal->id }})"
                                        class="px-3 py-1.5 rounded-lg text-sm font-semibold border transition-colors
                                               {{ $activeCalendarId === $cal->id ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">
                                    {{ $cal->year ?? $cal->title }}
                                </button>
                            @endforeach
                        @endif
                        <div class="flex rounded-lg border border-slate-200 bg-slate-100 p-0.5">
                            <button wire:click="setTerm(1)"
                                    class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors
                                           {{ $selectedTerm === 1 ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                Term 1
                            </button>
                            <button wire:click="setTerm(2)"
                                    class="px-4 py-1.5 rounded-md text-sm font-semibold transition-colors
                                           {{ $selectedTerm === 2 ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                Term 2
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats row --}}
            @php
                $statCards = [
                    ['label' => 'Teaching Days',    'value' => $stats[$selectedTerm]['teaching'], 'color' => 'text-blue-600'],
                    ['label' => 'Exam Days',         'value' => $stats[$selectedTerm]['exam'],     'color' => 'text-amber-600'],
                    ['label' => 'Holiday Days',      'value' => $stats[$selectedTerm]['holiday'],  'color' => 'text-red-600'],
                    ['label' => 'Total School Days', 'value' => $stats[$selectedTerm]['total'],    'color' => 'text-slate-700'],
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
                @foreach($statCards as $stat)
                    <div class="rounded-xl border border-slate-200 bg-white p-4 text-center">
                        <p class="text-2xl sm:text-3xl font-black {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                        <p class="text-xs font-semibold text-slate-500 mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Main area: calendar grid + side panel --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Monthly calendar grid (2/3 on lg) --}}
                <div class="lg:col-span-2">

                    {{-- Month navigation --}}
                    <div class="flex items-center justify-between mb-3">
                        <button wire:click="prevMonth" @disabled($calendarMonth <= 0)
                                class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <h3 class="text-lg font-black text-slate-900">{{ $currentMonthCarbon->format('F Y') }}</h3>
                        <button wire:click="nextMonth" @disabled($calendarMonth >= 12)
                                class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Weekday headers --}}
                    <div class="grid grid-cols-7 mb-1">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $dayLabel)
                            <div class="text-center text-[10px] font-bold text-slate-400 uppercase py-1.5">{{ $dayLabel }}</div>
                        @endforeach
                    </div>

                    {{-- Day grid --}}
                    @php
                        $gridFirstDay  = (int) $currentMonthCarbon->copy()->startOfMonth()->dayOfWeek;
                        $gridDaysCount = (int) $currentMonthCarbon->daysInMonth;
                        $gridCells     = (int) ceil(($gridFirstDay + $gridDaysCount) / 7) * 7;
                        $todayStr      = now()->format('Y-m-d');
                    @endphp

                    <div class="grid grid-cols-7 gap-px bg-slate-200 rounded-xl overflow-hidden border border-slate-200">
                        @for($cell = 0; $cell < $gridCells; $cell++)
                            @php
                                $dayNum    = $cell - $gridFirstDay + 1;
                                $isValid   = $dayNum >= 1 && $dayNum <= $gridDaysCount;
                                $dateStr   = $isValid ? $currentMonthCarbon->copy()->day($dayNum)->format('Y-m-d') : null;
                                $dayEvents = ($dateStr && isset($entriesByDate[$dateStr])) ? $entriesByDate[$dateStr] : [];
                                $isToday   = $dateStr === $todayStr;
                                $isWeekend = in_array($cell % 7, [0, 6]);
                            @endphp
                            <div class="min-h-[70px] sm:min-h-[80px] p-1 sm:p-1.5 {{ !$isValid ? 'bg-slate-50' : ($isWeekend ? 'bg-slate-50/70' : 'bg-white') }}">
                                @if($isValid)
                                    <div class="flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full mb-1 mx-auto
                                                {{ $isToday ? 'bg-rose-600 text-white font-black' : 'text-slate-600 font-semibold' }}
                                                text-[10px] sm:text-xs">
                                        {{ $dayNum }}
                                    </div>
                                    <div class="space-y-px">
                                        @foreach(array_slice($dayEvents, 0, 2) as $ev)
                                            <div class="text-[8px] sm:text-[9px] font-semibold px-1 py-px rounded truncate
                                                        {{ $calTypeColors[$ev->type] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $ev->title }}
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

                    {{-- Legend --}}
                    <div class="flex flex-wrap gap-3 mt-3">
                        @foreach(['holiday' => 'Holiday', 'term' => 'Academic', 'exam' => 'Exam', 'event' => 'Event'] as $type => $label)
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-sm {{ $calTypeColors[$type] }}"></span>
                                <span class="text-xs text-slate-500 font-medium">{{ $label }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Side panel (1/3 on lg) --}}
                <div class="space-y-4">

                    {{-- School Transfer Windows --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <h4 class="text-sm font-bold text-slate-900 mb-3">School Transfer Windows</h4>
                        <div class="space-y-2">
                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-slate-50">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">Period 1</p>
                                    <p class="text-xs text-slate-500">17 May – 15 Jun {{ $currentCalendar?->year ?? date('Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 p-2.5 rounded-lg bg-slate-50">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mt-1.5 flex-shrink-0"></div>
                                <div>
                                    <p class="text-xs font-semibold text-slate-700">Period 2</p>
                                    <p class="text-xs text-slate-500">18 Oct – 17 Nov {{ $currentCalendar?->year ?? date('Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Notes & Reminders --}}
                    <div class="rounded-xl border border-slate-200 bg-white p-4">
                        <h4 class="text-sm font-bold text-slate-900 mb-3">Notes & Reminders</h4>
                        <ul class="space-y-2">
                            @foreach([
                                'School transfers are only permitted during designated transfer windows.',
                                'Exam schedules are subject to change — consult your school for updates.',
                                'Public holidays follow the official Maldives government calendar.',
                                'Weekend school activities may affect the schedule.',
                            ] as $note)
                                <li class="flex items-start gap-2 text-xs text-slate-600 leading-relaxed">
                                    <span class="text-slate-300 mt-0.5 flex-shrink-0">•</span>
                                    {{ $note }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Tentative notice --}}
                    <div class="rounded-xl bg-slate-900 p-4 text-white">
                        <div class="flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-bold text-amber-400">Tentative Schedule</p>
                        </div>
                        <p class="text-xs text-slate-300 leading-relaxed">
                            This academic calendar is tentative and was issued on 10 August 2025. All dates are subject to change by the Ministry of Education.
                        </p>
                    </div>

                </div>
            </div>
        @endif

    </div>

</div>

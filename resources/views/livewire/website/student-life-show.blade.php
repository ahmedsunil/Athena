<div>
    @php
        $initialsFor = function (?string $name): string {
            $words = preg_split('/\s+/', trim((string) $name));
            $initials = array_map(fn ($word) => strtoupper($word[0] ?? ''), $words ?: []);

            return implode('', array_slice(array_filter($initials), 0, 2)) ?: 'HS';
        };

        $title = $item->name ?? $item->role ?? __('student_life_heading');
    @endphp

    <section class="border-b border-slate-200 bg-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('student-life.index', ['activeTab' => $backTab]) }}" class="mb-5 inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 transition-colors hover:text-[#002366]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to Student Life
            </a>
            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-[#002366]">{{ $typeLabel }}</p>
            <h1 class="text-3xl font-black text-slate-900 sm:text-4xl">{{ $title }}</h1>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="space-y-8">
            <section class="rounded-2xl border border-slate-200 bg-white p-7 sm:p-9">
                @if($type === 'clubs')
                    <div class="mb-6 flex items-start gap-4">
                        <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-[#002366]/10 text-[#002366] ring-1 ring-slate-200">
                            @if($item->logo_path)
                                <img src="{{ $item->logo_url }}" alt="{{ $item->name }}" class="h-full w-full object-contain p-2">
                            @else
                                <span class="text-2xl font-black">{{ $item->initials }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-[#002366]">{{ __('student_life_tab_clubs') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $item->name }}</h2>
                        </div>
                    </div>

                    @if($item->description)
                        <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                            @foreach(explode("\n\n", $item->description) as $paragraph)
                                @if(trim($paragraph))
                                    <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @elseif($type === 'houses')
                    @php $cc = $item->colour_classes; @endphp
                    <div class="mb-6 flex items-start gap-4">
                        <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-2xl {{ $cc['bg_light'] }} {{ $cc['text'] }}">
                            <svg viewBox="0 0 24 24" class="h-12 w-12" fill="currentColor">
                                <path d="M12 1 3 5v7c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5L12 1Zm0 4 5 2.18V12c0 3.5-2.33 6.79-5 7.93-2.67-1.14-5-4.43-5-7.93V7.18L12 5Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest {{ $cc['text'] }}">{{ __('student_life_tab_houses') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $item->name }}</h2>
                            @if($item->motto)
                                <p class="mt-1 text-sm font-semibold italic {{ $cc['text'] }}">"{{ $item->motto }}"</p>
                            @endif
                        </div>
                    </div>

                    @if($item->description)
                        <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                            @foreach(explode("\n\n", $item->description) as $paragraph)
                                @if(trim($paragraph))
                                    <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @elseif($type === 'prefects')
                    <div class="mb-6 flex items-start gap-4">
                        @if($item->photo_path)
                            <img src="{{ $item->photo_url }}" alt="{{ $item->name }}" class="h-24 w-24 flex-shrink-0 rounded-full object-cover ring-4 {{ $item->role_colour }}">
                        @else
                            <div class="flex h-24 w-24 flex-shrink-0 items-center justify-center rounded-full text-2xl font-black ring-4 {{ $item->role_colour }}">
                                {{ $item->initials }}
                            </div>
                        @endif
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-[#002366]">{{ __('student_life_tab_prefects') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $item->name }}</h2>
                            <p class="mt-1 text-sm font-semibold text-[#002366]">{{ $item->role }}</p>
                            @if($item->class_name)
                                <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ $item->class_name }}</span>
                            @endif
                        </div>
                    </div>

                    @if($item->quote)
                        <blockquote class="rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm italic leading-relaxed text-slate-600">
                            "{{ $item->quote }}"
                        </blockquote>
                    @endif
                @else
                    @php $cc = $item->colour_classes; @endphp
                    <div class="mb-6 flex items-start gap-4">
                        <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-2xl {{ $cc['bg_light'] }} {{ $cc['text'] }}">
                            @if($item->logo_path)
                                <img src="{{ $item->logo_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.562.562 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5Z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest {{ $cc['text'] }}">{{ $item->group_type }}</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $item->name }}</h2>
                        </div>
                    </div>

                    @if($item->description)
                        <div class="prose prose-slate max-w-none text-sm leading-relaxed text-slate-700">
                            @foreach(explode("\n\n", $item->description) as $paragraph)
                                @if(trim($paragraph))
                                    <p>{{ trim($paragraph) }}</p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif
            </section>

            @if($hasPeopleHistory || collect($fallbackPeople)->filter(fn ($person) => filled($person['name'] ?? null))->isNotEmpty())
                <section class="rounded-2xl border border-slate-200 bg-white p-7 sm:p-9">
                    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-[#002366]">People</p>
                            <h2 class="mt-2 text-xl font-black text-slate-900">Leadership History</h2>
                        </div>

                        @if($hasPeopleHistory)
                            <div class="flex flex-wrap gap-2">
                                @if($hasActivePeople)
                                    <a href="{{ route('student-life.show', ['type' => $type, 'id' => $item->id, 'people' => 'active']) }}"
                                            class="rounded-sm border px-3 py-2 text-xs font-bold transition-colors {{ $peopleFilter === 'active' ? 'border-[#002366] bg-[#002366] text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-[#002366]/40 hover:text-[#002366]' }}">
                                        Active
                                    </a>
                                @endif
                                @foreach($peopleYears as $year)
                                    <a href="{{ route('student-life.show', ['type' => $type, 'id' => $item->id, 'people' => $year]) }}"
                                            class="rounded-sm border px-3 py-2 text-xs font-bold transition-colors {{ (string) $peopleFilter === (string) $year ? 'border-[#002366] bg-[#002366] text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-[#002366]/40 hover:text-[#002366]' }}">
                                        {{ $year }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($hasPeopleHistory)
                        <x-website.student-life.people-list :people="$people" :initials-for="$initialsFor" />
                    @else
                        <x-website.student-life.people-list :people="$fallbackPeople" :initials-for="$initialsFor" />
                    @endif
                </section>
            @endif
        </div>
    </main>
</div>

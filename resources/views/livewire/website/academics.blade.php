<div>

    @php
    $stageColors = [
        'FS'  => 'bg-rose-100 text-rose-700',
        'KS1' => 'bg-sky-100 text-sky-700',
        'KS2' => 'bg-emerald-100 text-emerald-700',
        'KS3' => 'bg-violet-100 text-violet-700',
        'KS4' => 'bg-amber-100 text-amber-700',
        'KS5' => 'bg-slate-100 text-slate-700',
    ];
    @endphp

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2" data-reveal="fade">Our Curriculum</p>
                    <h1 class="text-3xl sm:text-4xl font-black text-slate-900 mb-3" data-reveal="left">Academics</h1>
                    @if($overview->text)
                        <p class="text-slate-500 max-w-2xl text-sm leading-relaxed" data-reveal="fade">{{ $overview->text }}</p>
                    @endif
                    @if($overview->curriculum)
                        <span class="mt-3 inline-block text-xs font-semibold bg-sky-100 text-sky-700 px-3 py-1 rounded-full" data-reveal="fade">{{ $overview->curriculum }}</span>
                    @endif
                </div>
                <div class="lg:ml-auto grid grid-cols-2 gap-3 sm:flex sm:items-center" data-reveal="right">
                    <div class="h-20 sm:h-24 w-full sm:w-52 rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/cambridge-international-education.svg') }}" alt="Cambridge International Education" class="max-h-12 w-full object-contain">
                    </div>
                    <div class="h-20 sm:h-24 w-full sm:w-52 rounded-2xl border border-slate-200 bg-white px-4 py-3 flex items-center justify-center shadow-sm">
                        <img src="{{ asset('images/pearson-edexcel.png') }}" alt="Pearson Edexcel" class="max-h-12 w-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Level cards --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if($levels->isEmpty())
            <div class="text-center py-20 text-slate-400">
                <p class="text-lg font-semibold">No academic levels published yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($levels as $level)
                <article class="bg-white rounded-2xl border border-slate-200 p-5 hover:shadow-md transition-shadow" data-reveal="scale" style="--reveal-delay: {{ $loop->index * 60 }}ms">

                    {{-- Header: badge + age --}}
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <span class="inline-flex text-xs font-bold uppercase tracking-widest px-2.5 py-1 rounded-full {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">{{ $level->abbreviation }}</span>
                            <h2 class="text-xl font-black text-slate-900 mt-3">{{ $level->label }}</h2>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Age group</p>
                            <p class="text-xs font-semibold text-slate-700">{{ $level->age_range }}</p>
                        </div>
                    </div>

                    {{-- Grades + Lead teacher --}}
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Grades</p>
                            <p class="text-sm font-semibold text-slate-900">{{ $level->year_groups }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Leading teacher</p>
                            <div class="flex items-center gap-2">
                                @if($level->lead_teacher_photo_path)
                                    <img src="{{ $level->lead_teacher_photo_url }}" alt="{{ $level->lead_teacher }}" class="w-8 h-8 rounded-full flex-shrink-0 object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black {{ $stageColors[$level->abbreviation] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $level->initials }}
                                    </div>
                                @endif
                                <p class="text-sm font-semibold text-slate-900 leading-tight">{{ $level->lead_teacher }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Streams (optional) --}}
                    @if($level->streams && count($level->streams) > 0)
                        <div class="mb-4 flex flex-wrap gap-2">
                            @foreach($level->streams as $stream)
                                <span class="text-xs font-semibold bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full">{{ $stream }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Subjects --}}
                    @if($level->subjects && count($level->subjects) > 0)
                        <div class="mb-4">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2">Subjects</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($level->subjects as $subject)
                                    <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg">{{ $subject }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Targets --}}
                    @if($level->targets && count($level->targets) > 0)
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-2">Targets</p>
                            <ul class="space-y-1.5">
                                @foreach($level->targets as $target)
                                    <li class="text-xs text-slate-600 flex gap-2">
                                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-rose-400 flex-shrink-0"></span>
                                        {{ $target }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </article>
                @endforeach
            </div>
        @endif
    </div>

</div>

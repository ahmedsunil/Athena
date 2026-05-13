<div>

    {{-- Hero image --}}
    <div class="relative h-64 sm:h-80 lg:h-96 overflow-hidden">
        @if($event->cover_image_path)
            <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-slate-800 via-rose-950 to-slate-900"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-900/30 to-transparent"></div>
        <div class="absolute bottom-4 left-4">
            <span class="inline-block text-[10px] font-bold uppercase tracking-wide px-2.5 py-1 rounded-full
                {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600') }}">
                {{ ucfirst($event->status) }}
            </span>
        </div>
    </div>

    {{-- Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Back link --}}
        <a href="{{ route('events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-rose-600 transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Back to Events
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Main content (2/3) --}}
            <div class="lg:col-span-2">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 mb-6">{{ $event->title }}</h1>

                <div class="bg-white rounded-2xl border border-slate-200 p-7 sm:p-9">
                    <div class="space-y-4 text-slate-700 text-sm leading-relaxed">
                        @if($event->full_description)
                            @foreach(explode("\n\n", $event->full_description) as $para)
                                @if(trim($para))
                                    <p>{{ trim($para) }}</p>
                                @endif
                            @endforeach
                        @else
                            <p>{{ $event->short_description }}</p>
                        @endif
                    </div>
                </div>

                {{-- Attachments / Downloads --}}
                @if($event->attachments && count($event->attachments) > 0)
                    <div class="mt-8">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">Downloads</p>
                        <div class="space-y-2">
                            @foreach($event->attachments as $attachment)
                                @if(!empty($attachment['url']))
                                    <a href="{{ $attachment['url'] }}" target="_blank" rel="noopener"
                                       class="flex items-center gap-3 bg-slate-50 hover:bg-rose-50 border border-slate-100 hover:border-rose-200 rounded-xl p-3.5 transition-all">
                                        <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-slate-900">{{ $attachment['label'] ?: $attachment['url'] }}</p>
                                        </div>
                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar (1/3) --}}
            <div class="space-y-4">

                {{-- Event details --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-4">Event Details</p>
                    <dl class="space-y-3">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                <dt class="text-xs text-slate-400">Date</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ $event->formatted_date_range }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div>
                                <dt class="text-xs text-slate-400">Location</dt>
                                <dd class="text-sm font-semibold text-slate-900">{{ $event->location }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <dt class="text-xs text-slate-400">Status</dt>
                                <dd class="text-sm font-semibold
                                    {{ $event->status === 'ongoing' ? 'text-emerald-600' : ($event->status === 'upcoming' ? 'text-sky-600' : 'text-slate-600') }}">
                                    {{ ucfirst($event->status) }}
                                </dd>
                            </div>
                        </div>
                    </dl>
                </div>

                {{-- Contact --}}
                @if($event->contact)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5">
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">Contact</p>
                        <p class="text-sm text-slate-700">{{ $event->contact }}</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>

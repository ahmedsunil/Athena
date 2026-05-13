<div>

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2" data-reveal="fade">School Events</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900" data-reveal="left">Events</h1>
        </div>
    </section>

    {{-- Filter tabs + grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Filter pills --}}
        <div class="flex gap-2 flex-wrap mb-8">
            @foreach(['all' => 'All', 'ongoing' => 'Ongoing', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $value => $label)
                <button wire:click="setFilter('{{ $value }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                               {{ $filter === $value
                                   ? 'bg-rose-600 border-rose-600 text-white'
                                   : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Events grid --}}
        @if($events->isEmpty())
            <div class="text-center py-20 text-slate-400">
                <p class="text-lg font-semibold">No {{ $filter !== 'all' ? $filter : '' }} events at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow" data-reveal="scale" style="--reveal-delay: {{ $loop->index * 60 }}ms">
                        {{-- Cover image --}}
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            @if($event->cover_image_path)
                                <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full
                                {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $event->status }}
                            </span>
                        </div>

                        {{-- Card body --}}
                        <div class="p-5">
                            <p class="text-xs text-sky-600 font-semibold mb-1">{{ $event->formatted_date_range }}</p>
                            <h3 class="font-bold text-slate-900 mb-1">{{ $event->title }}</h3>
                            <p class="text-xs text-slate-500 mb-3 flex items-start gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->location }}
                            </p>
                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $event->short_description }}</p>
                            <a href="{{ route('events.show', $event->slug) }}"
                               class="block w-full text-center text-sm font-semibold text-rose-600 hover:text-rose-700 border border-rose-200 hover:border-rose-300 rounded-xl py-2 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

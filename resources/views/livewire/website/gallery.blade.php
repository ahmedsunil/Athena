<div>

    {{-- Page header --}}
    <div class="bg-white border-b border-slate-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">Photo Albums</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">Gallery</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Category pills --}}
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <button wire:click="setCategory('All')"
                    class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                           {{ $activeCategory === 'All' ? 'bg-rose-600 border-rose-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                All
            </button>
            @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                               {{ $activeCategory === $cat ? 'bg-rose-600 border-rose-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                    {{ $cat }}
                </button>
            @endforeach
        </div>

        {{-- Year / month selects + result count + clear --}}
        <div class="flex flex-wrap items-center gap-2 mb-8">
            @if($albums->count() < $total)
                <span class="text-xs text-slate-400">{{ $albums->count() }} album{{ $albums->count() !== 1 ? 's' : '' }}</span>
            @endif
            <div class="ml-auto flex items-center gap-2 flex-wrap">
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

        {{-- Albums grid --}}
        @if($albums->isEmpty())
            <div class="col-span-4 text-center py-16 text-slate-400">
                <p class="font-semibold">No albums found</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($albums as $album)
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all hover:-translate-y-0.5">
                        {{-- Cover image --}}
                        <div class="relative h-44 overflow-hidden bg-slate-100">
                            @if($album->cover_image_path)
                                <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            <span class="absolute bottom-2 left-2 text-[10px] font-bold uppercase tracking-wider bg-white/90 text-slate-700 px-2 py-0.5 rounded">
                                {{ $album->category }}
                            </span>
                            @if($album->photo_count > 0)
                                <span class="absolute bottom-2 right-2 text-[10px] text-white/80 flex items-center gap-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $album->photo_count }}
                                </span>
                            @endif
                        </div>
                        {{-- Card body --}}
                        <div class="p-4">
                            <h3 class="font-bold text-slate-900 text-sm leading-snug mb-1">{{ $album->title }}</h3>
                            <p class="text-xs text-slate-400 mb-3">{{ $album->date->format('d M Y') }}</p>
                            @if($album->facebook_url)
                                <a href="{{ $album->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-sky-600 hover:text-sky-700">
                                    View on Facebook
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @else
                                <span class="text-xs text-slate-300">No Facebook link</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

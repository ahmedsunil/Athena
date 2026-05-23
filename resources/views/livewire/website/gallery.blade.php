<div>

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-reveal="fade">
            <p class="text-xs font-bold uppercase tracking-widest text-[#002366] mb-2">{{ __('gallery_page_label') }}</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900">{{ __('gallery_heading') }}</h1>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @php
            $categoryLabels = [
                'Events' => 'gallery_category_events',
                'Sports' => 'gallery_category_sports',
                'Graduation' => 'gallery_category_graduation',
                'Cultural' => 'gallery_category_cultural',
                'Academic' => 'gallery_category_academic',
                'Trips' => 'gallery_category_trips',
            ];
            $localizedDate = fn ($date) => $date->format('d') . ' ' . __('common_month_short_' . $date->month) . ' ' . $date->format('Y');
        @endphp

        {{-- Filters row: category pills + selects + clear --}}
        <div class="flex flex-wrap items-center gap-2 mb-8" data-reveal="fade">
            <button wire:click="setCategory('All')"
                    class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                           {{ $activeCategory === 'All' ? 'bg-[#002366] border-[#002366] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                {{ __('common_all') }}
            </button>
            @foreach($categories as $cat)
                <button wire:click="setCategory('{{ $cat }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                               {{ $activeCategory === $cat ? 'bg-[#002366] border-[#002366] text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                    {{ __($categoryLabels[$cat] ?? $cat) }}
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

        {{-- Albums grid --}}
        @if($albums->isEmpty())
            <div class="col-span-4 text-center py-16 text-slate-400">
                <p class="font-semibold">{{ __('gallery_empty') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($albums as $i => $album)
                    <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all hover:-translate-y-0.5"
                         data-reveal="scale" style="--reveal-delay: {{ ($i % 8) * 55 }}ms">
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
                                {{ __($categoryLabels[$album->getTranslation('category', 'en', false)] ?? $album->category) }}
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
                            <p class="text-xs text-slate-400 mb-3">{{ $localizedDate($album->date) }}</p>
                            @if($album->facebook_url)
                                <a href="{{ $album->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#002366] hover:text-[#002366]">
                                    {{ __('gallery_view_on_facebook') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @else
                                <span class="text-xs text-slate-300">{{ __('gallery_no_facebook_link') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

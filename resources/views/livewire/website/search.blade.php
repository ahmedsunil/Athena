<div x-data="siteSearch()" x-cloak @open-site-search.window="openSearch()" @keydown.escape.window="closeSearch()">
    <template x-if="open">
        <div>
            <button type="button" @click="closeSearch()" class="fixed inset-0 z-[998] bg-[#002366]/60 backdrop-blur-sm" aria-label="Close search"></button>

            <div class="fixed left-1/2 top-[10vh] z-[999] w-full max-w-xl -translate-x-1/2 px-4">
                <div class="overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10">
                    <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3">
                        <svg class="h-5 w-5 flex-shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                        </svg>
                        <input
                            x-ref="input"
                            x-model.debounce.250ms="query"
                            @input.debounce.250ms="runSearch()"
                            type="text"
                            placeholder="{{ __('search_placeholder') }}"
                            class="flex-1 bg-transparent text-sm text-slate-900 outline-none placeholder-slate-400"
                            autocomplete="off"
                            spellcheck="false"
                        >
                        <button
                            type="button"
                            @click="closeSearch()"
                            class="flex-shrink-0 rounded-md border border-slate-200 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 hover:bg-slate-50"
                        >{{ __('search_escape') }}</button>
                    </div>

                    <template x-if="query.trim().length >= 2">
                        <div>
                            <template x-if="loading">
                                <div class="px-4 py-10 text-center">
                                    <p class="text-sm font-semibold text-slate-500">Searching...</p>
                                </div>
                            </template>

                            <template x-if="!loading && results.length">
                                <div class="max-h-[60vh] overflow-y-auto py-2">
                                    <template x-for="section in groupedSections()" :key="section.name">
                                        <div class="px-3 pb-1 pt-2">
                                            <p class="mb-1 px-1 text-[10px] font-bold uppercase tracking-widest" :class="section.textClass" x-text="section.label"></p>
                                            <template x-for="item in section.items" :key="item.url + item.title">
                                                <a :href="item.url" @click="closeSearch()" class="spotlight-item flex items-start gap-3 rounded-xl px-3 py-2.5 transition-colors hover:bg-slate-50 focus:bg-slate-50 focus:outline-none">
                                                    <span class="mt-0.5 flex-shrink-0 rounded-md p-1.5" :class="section.bgClass">
                                                        <svg x-show="section.name === 'Events'" class="h-3.5 w-3.5" :class="section.textClass" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                        <svg x-show="section.name !== 'Events'" class="h-3.5 w-3.5" :class="section.textClass" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </span>
                                                    <span class="min-w-0">
                                                        <span class="block truncate text-sm font-semibold text-slate-900" x-text="item.title"></span>
                                                        <span x-show="item.snippet" class="mt-0.5 block truncate text-xs text-slate-400" x-text="item.snippet"></span>
                                                    </span>
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!loading && !results.length">
                                <div class="px-4 py-10 text-center">
                                    <p class="text-sm font-semibold text-slate-500">{{ __('search_no_results') }} "<span x-text="query"></span>"</p>
                                    <p class="mt-1 text-xs text-slate-400">{{ __('search_try_different') }}</p>
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="query.trim().length < 2">
                        <div class="p-4">
                            <p class="mb-2 px-1 text-[10px] font-bold uppercase tracking-widest text-slate-400">{{ __('search_quick_links') }}</p>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach([
                                    ['label' => __('nav_events'),           'url' => route('events.index'),           'icon' => 'calendar'],
                                    ['label' => __('nav_academics'),        'url' => route('academics.index'),        'icon' => 'book'],
                                    ['label' => __('nav_digital_services'), 'url' => route('digital-services.index'), 'icon' => 'document'],
                                ] as $link)
                                    <a href="{{ $link['url'] }}" @click="closeSearch()" class="spotlight-item flex items-center gap-2.5 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-100 focus:bg-slate-100 focus:outline-none">
                                        @if($link['icon'] === 'calendar')
                                            <svg class="h-4 w-4 text-[#002366]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        @elseif($link['icon'] === 'book')
                                            <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        @else
                                            <svg class="h-4 w-4 text-[#B21F2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                        {{ $link['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function siteSearch() {
        return {
            open: false,
            query: '',
            results: [],
            loading: false,
            controller: null,
            labels: {
                Events: @js(__('nav_events')),
                Downloads: @js(__('nav_downloads')),
                Announcements: @js(__('common_announcements')),
            },
            styles: {
                Events: { bgClass: 'bg-[#002366]/10', textClass: 'text-[#002366]' },
                Downloads: { bgClass: 'bg-[#B21F2D]/10', textClass: 'text-[#B21F2D]' },
                Announcements: { bgClass: 'bg-[#002366]/10', textClass: 'text-[#002366]' },
            },
            openSearch() {
                this.open = true;
                this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
            },
            closeSearch() {
                this.open = false;
                this.query = '';
                this.results = [];
                this.loading = false;

                if (this.controller) {
                    this.controller.abort();
                    this.controller = null;
                }
            },
            async runSearch() {
                const term = this.query.trim();
                if (term.length < 2) {
                    this.results = [];
                    this.loading = false;
                    return;
                }

                if (this.controller) {
                    this.controller.abort();
                }

                this.controller = new AbortController();
                this.loading = true;

                try {
                    const response = await fetch(@js(route('website.search')) + '?q=' + encodeURIComponent(term), {
                        headers: { Accept: 'application/json' },
                        signal: this.controller.signal,
                    });
                    const payload = await response.json();
                    this.results = payload.results || [];
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        this.results = [];
                    }
                } finally {
                    this.loading = false;
                }
            },
            groupedSections() {
                const grouped = {};
                this.results.forEach((item) => {
                    grouped[item.section] = grouped[item.section] || [];
                    grouped[item.section].push(item);
                });

                return Object.entries(grouped).map(([name, items]) => ({
                    name,
                    items,
                    label: this.labels[name] || name,
                    bgClass: (this.styles[name] || {}).bgClass || 'bg-slate-100',
                    textClass: (this.styles[name] || {}).textClass || 'text-slate-500',
                }));
            },
        };
    }
</script>

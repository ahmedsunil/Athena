<div
    x-data="{ open: false }"
    x-on:open-search.window="open = true; $nextTick(() => $refs.searchInput && $refs.searchInput.focus())"
    x-on:keydown.escape.window="open = false"
    x-on:keydown.meta.k.window.prevent="open = true; $nextTick(() => $refs.searchInput && $refs.searchInput.focus())"
    x-on:keydown.ctrl.k.window.prevent="open = true; $nextTick(() => $refs.searchInput && $refs.searchInput.focus())"
>
    {{-- Backdrop --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="open = false"
        class="fixed inset-0 z-[998] bg-slate-900/60 backdrop-blur-sm"
        style="display:none"
    ></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="fixed left-1/2 top-[10vh] z-[999] w-full max-w-xl -translate-x-1/2 px-4"
        style="display:none"
        x-on:keydown.arrow-down.prevent="
            const items = $el.querySelectorAll('.spotlight-item');
            const idx = Array.from(items).indexOf(document.activeElement);
            if (idx < items.length - 1) items[idx + 1].focus();
        "
        x-on:keydown.arrow-up.prevent="
            const items = $el.querySelectorAll('.spotlight-item');
            const idx = Array.from(items).indexOf(document.activeElement);
            if (idx > 0) items[idx - 1].focus();
            else $refs.searchInput.focus();
        "
    >
        <div class="rounded-2xl bg-white shadow-2xl ring-1 ring-slate-900/10 overflow-hidden">
            {{-- Search input --}}
            <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100">
                <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input
                    x-ref="searchInput"
                    wire:model.live.debounce.250ms="query"
                    type="text"
                    placeholder="{{ __('search_placeholder') }}"
                    class="flex-1 bg-transparent text-sm text-slate-900 placeholder-slate-400 outline-none"
                    autocomplete="off"
                    spellcheck="false"
                    x-on:keydown.enter.prevent="
                        const first = $el.closest('.rounded-2xl').querySelector('.spotlight-item');
                        if (first) first.click();
                    "
                >
                <button
                    x-on:click="open = false"
                    class="flex-shrink-0 rounded-md border border-slate-200 px-1.5 py-0.5 text-[10px] font-semibold text-slate-400 hover:bg-slate-50"
                >ESC</button>
            </div>

            {{-- Results --}}
            @php
                $grouped = collect($results)->groupBy('section');
                $sectionColors = [
                    'Events'    => ['bg' => 'bg-sky-100',    'text' => 'text-sky-700'],
                    'Gallery'   => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700'],
                    'Downloads' => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700'],
                    'Announcements' => ['bg' => 'bg-[#002366]/10', 'text' => 'text-[#002366]'],
                ];
            @endphp

            @if(strlen(trim($query)) >= 2)
                @if($results)
                    <div class="max-h-[60vh] overflow-y-auto py-2">
                        @foreach($grouped as $section => $items)
                            <div class="px-3 pt-2 pb-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest {{ $sectionColors[$section]['text'] ?? 'text-slate-500' }} px-1 mb-1">{{ $section }}</p>
                                @foreach($items as $item)
                                    <a
                                        href="{{ $item['url'] }}"
                                        x-on:click="open = false"
                                        class="spotlight-item flex items-start gap-3 rounded-xl px-3 py-2.5 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none transition-colors"
                                    >
                                        <span class="mt-0.5 flex-shrink-0 rounded-md {{ $sectionColors[$section]['bg'] ?? 'bg-slate-100' }} p-1.5">
                                            @if($section === 'Events')
                                                <svg class="w-3.5 h-3.5 {{ $sectionColors[$section]['text'] ?? '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @elseif($section === 'Gallery')
                                                <svg class="w-3.5 h-3.5 {{ $sectionColors[$section]['text'] ?? '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 {{ $sectionColors[$section]['text'] ?? '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            @endif
                                        </span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900 truncate">{{ $item['title'] }}</p>
                                            @if($item['snippet'])
                                                <p class="text-xs text-slate-400 truncate mt-0.5">{{ $item['snippet'] }}</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-4 py-10 text-center">
                        <p class="text-sm font-semibold text-slate-500">{{ __('search_no_results') }} "{{ $query }}"</p>
                        <p class="text-xs text-slate-400 mt-1">{{ __('search_try_different') }}</p>
                    </div>
                @endif
            @else
                {{-- Empty state — quick links --}}
                <div class="p-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2 px-1">{{ __('search_quick_links') }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach([
                            ['label' => 'Events',          'url' => route('events.index'),          'color' => 'sky',    'icon' => 'calendar'],
                            ['label' => 'Academics',       'url' => route('academics.index'),       'color' => 'emerald','icon' => 'book'],
                            ['label' => 'Gallery',         'url' => route('gallery.index'),         'color' => 'violet', 'icon' => 'photo'],
                            ['label' => 'Digital Services','url' => route('digital-services.index'),'color' => 'amber',  'icon' => 'document'],
                        ] as $link)
                            <a
                                href="{{ $link['url'] }}"
                                x-on:click="open = false"
                                class="spotlight-item flex items-center gap-2.5 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100 focus:bg-slate-100 focus:outline-none transition-colors"
                            >
                                @if($link['icon'] === 'calendar')
                                    <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @elseif($link['icon'] === 'book')
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @elseif($link['icon'] === 'photo')
                                    <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @else
                                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                @endif
                                {{ $link['label'] }}
                            </a>
                        @endforeach
                    </div>
                    <p class="text-center text-[10px] text-slate-300 mt-3">{{ __('search_keyboard_hint') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

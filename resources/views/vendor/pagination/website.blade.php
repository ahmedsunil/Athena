@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm font-medium text-slate-500">
            <span>{{ __('Showing') }}</span>
            <span class="font-bold text-slate-700">{{ $paginator->firstItem() }}</span>
            <span>{{ __('to') }}</span>
            <span class="font-bold text-slate-700">{{ $paginator->lastItem() }}</span>
            <span>{{ __('of') }}</span>
            <span class="font-bold text-slate-700">{{ $paginator->total() }}</span>
            <span>{{ __('results') }}</span>
        </p>

        <div class="flex flex-wrap items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-10 min-w-10 cursor-default items-center justify-center rounded-sm border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-slate-300">
                    <span class="sr-only">{{ __('pagination.previous') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </span>
            @else
                <button type="button"
                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        class="inline-flex h-10 min-w-10 items-center justify-center rounded-sm border border-[#002366]/20 bg-white px-3 text-sm font-bold text-[#002366] transition-colors hover:border-[#002366] hover:bg-[#002366]/5 focus:outline-none focus:ring-2 focus:ring-[#002366]/25 disabled:opacity-60">
                    <span class="sr-only">{{ __('pagination.previous') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                </button>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-10 min-w-10 cursor-default items-center justify-center rounded-sm border border-slate-200 bg-white px-3 text-sm font-bold text-slate-400">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-10 min-w-10 items-center justify-center rounded-sm border border-[#002366] bg-[#002366] px-3 text-sm font-bold text-white">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button"
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    wire:loading.attr="disabled"
                                    class="inline-flex h-10 min-w-10 items-center justify-center rounded-sm border border-slate-200 bg-white px-3 text-sm font-bold text-slate-600 transition-colors hover:border-[#002366]/40 hover:bg-[#002366]/5 hover:text-[#002366] focus:outline-none focus:ring-2 focus:ring-[#002366]/25 disabled:opacity-60">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button type="button"
                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                        wire:loading.attr="disabled"
                        class="inline-flex h-10 min-w-10 items-center justify-center rounded-sm border border-[#002366]/20 bg-white px-3 text-sm font-bold text-[#002366] transition-colors hover:border-[#002366] hover:bg-[#002366]/5 focus:outline-none focus:ring-2 focus:ring-[#002366]/25 disabled:opacity-60">
                    <span class="sr-only">{{ __('pagination.next') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </button>
            @else
                <span class="inline-flex h-10 min-w-10 cursor-default items-center justify-center rounded-sm border border-slate-200 bg-slate-50 px-3 text-sm font-bold text-slate-300">
                    <span class="sr-only">{{ __('pagination.next') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

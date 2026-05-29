<div x-data="{ filter: @js($filter) }">

    <section class="border-b border-slate-200 bg-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-[#002366]" data-reveal="fade">{{ __('announcements_page_label') }}</p>
            <h1 class="text-3xl font-black text-slate-900 sm:text-4xl" data-reveal="left">{{ __('announcements_heading') }}</h1>
        </div>
    </section>

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @php
            $localizedDate = fn ($date) => $date->format('j') . ' ' . __('common_month_short_' . $date->month) . ' ' . $date->format('Y');
        @endphp
        <div class="mb-8 flex flex-wrap gap-2">
            <a href="{{ route('announcements.index', ['filter' => 'active']) }}"
                    class="rounded-full border px-4 py-2 text-sm font-semibold transition-colors {{ $filter === 'active' ? 'border-[#002366] bg-[#002366] text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                {{ __('announcements_filter_active') }}
            </a>
            <a href="{{ route('announcements.index', ['filter' => 'closed']) }}"
                    class="rounded-full border px-4 py-2 text-sm font-semibold transition-colors {{ $filter === 'closed' ? 'border-[#002366] bg-[#002366] text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300' }}">
                {{ __('announcements_filter_closed') }}
            </a>
        </div>

        @if($announcements->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-16 text-center">
                <p class="text-lg font-semibold text-slate-500">{{ __('announcements_empty') }}</p>
            </div>
        @else
            <div class="grid gap-4 lg:grid-cols-2">
                @foreach($announcements as $announcement)
                    <article
                        role="link"
                        tabindex="0"
                        onclick="window.location.href = @js(route('announcements.show', $announcement->slug))"
                        onkeydown="if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); window.location.href = @js(route('announcements.show', $announcement->slug)); }"
                        class="cursor-pointer rounded-2xl border border-slate-200 bg-white p-5 transition-shadow hover:shadow-md focus:outline-none focus:ring-2 focus:ring-[#002366] focus:ring-offset-2"
                        data-reveal="fade" style="--reveal-delay: {{ $loop->index * 45 }}ms">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#002366]/5 text-[#002366]">
                                <x-icon :key="$announcement->icon_key" class="h-4 w-4" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="mb-2 flex flex-wrap items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wide text-[#002366]">{{ $announcement->category }}</span>
                                    <span class="text-xs font-semibold text-slate-400">{{ $localizedDate($announcement->created_at) }}</span>
                                    @if($announcement->deadline)
                                        <span class="text-xs font-semibold text-slate-400">{{ __('announcements_deadline') }} {{ $localizedDate($announcement->deadline) }}</span>
                                    @endif
                                </div>
                                <h2 class="text-lg font-black text-slate-900">{{ $announcement->title }}</h2>
                                @if($announcement->description)
                                    <p class="mt-2 max-w-3xl text-sm leading-relaxed text-slate-600">{{ $announcement->description }}</p>
                                @endif
                                <div class="mt-4 flex flex-wrap items-center gap-3">
                                    <a href="{{ route('announcements.show', $announcement->slug) }}"
                                       onclick="event.stopPropagation()"
                                       class="inline-flex text-sm font-semibold text-[#002366] hover:text-[#002366]">
                                        {{ __('announcements_view_details') }}
                                    </a>
                                    @if($announcement->attachment_links)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400">
                                            <x-icon key="File" class="h-3.5 w-3.5" />
                                            {{ count($announcement->attachment_links) }} {{ count($announcement->attachment_links) === 1 ? __('announcements_file_singular') : __('announcements_file_plural') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if($announcements->hasPages())
                <div class="mt-8">
                    {{ $announcements->links('vendor.pagination.website') }}
                </div>
            @endif
        @endif
    </div>

</div>

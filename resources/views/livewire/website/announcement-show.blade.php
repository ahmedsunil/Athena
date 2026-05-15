<div>
    <section class="border-b border-slate-200 bg-white py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <a href="{{ route('announcements.index') }}"
               class="mb-4 inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-rose-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                {{ __('announcement_show_back') }}
            </a>
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <x-icon :key="$announcement->icon_key" class="h-6 w-6" />
                </div>
                <div class="min-w-0">
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">{{ $announcement->category }}</span>
                        <span class="text-xs font-medium text-slate-400">{{ __('announcement_show_created') }} {{ $announcement->created_at->format('j M Y') }}</span>
                        @if($announcement->deadline)
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">{{ __('announcement_show_deadline') }} {{ $announcement->formatted_deadline }}</span>
                        @endif
                    </div>
                    <h1 class="text-3xl font-black leading-tight text-slate-900 sm:text-4xl">{{ $announcement->title }}</h1>
                </div>
            </div>
        </div>
    </section>

    <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-slate-200 bg-white p-6">
            @if($announcement->description)
                <div class="prose prose-slate max-w-none">
                    <p class="whitespace-pre-line text-base leading-8 text-slate-700">{{ $announcement->description }}</p>
                </div>
            @else
                <p class="text-slate-400">{{ __('announcement_show_no_details') }}</p>
            @endif

            @if($announcement->attachment_links)
                <div class="mt-8 border-t border-slate-100 pt-6">
                    <h2 class="mb-3 text-sm font-bold uppercase tracking-widest text-slate-400">{{ __('announcement_show_attached_files') }}</h2>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($announcement->attachment_links as $attachment)
                            <a href="{{ $attachment['url'] }}" target="_blank" rel="noopener"
                               class="flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition-colors hover:border-rose-200 hover:text-rose-600">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500">
                                    <x-icon key="File" class="h-4 w-4" />
                                </span>
                                <span class="min-w-0 flex-1 truncate">{{ $attachment['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>

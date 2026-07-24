<div>

    {{-- Page header --}}
    <section class="border-b border-slate-200 bg-white py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal="fade">
            <p class="mb-2 text-xs font-bold uppercase tracking-widest text-[#002366]">{{ __('apps_page_label') }}</p>
            <h1 class="text-3xl font-black text-slate-900 sm:text-4xl">{{ __('apps_heading') }}</h1>
        </div>
    </section>

    {{-- App cards --}}
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if($apps->isEmpty())
            <p class="text-center text-sm text-slate-500">{{ __('apps_empty') }}</p>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($apps as $app)
                    <a href="{{ $app->url }}" target="_blank" rel="noopener noreferrer"
                       class="group flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md">
                        <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-[#002366]/10 text-[#002366]">
                            {!! svg_icon($app->icon_key, 'h-7 w-7') !!}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-base font-bold text-slate-900">{{ $app->title }}</h3>
                            @if($app->subtitle)
                                <p class="mt-0.5 text-sm text-slate-500">{{ $app->subtitle }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Quote --}}
    <section class="border-t border-slate-100 bg-white py-14">
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8" data-reveal="fade">
            <blockquote class="text-base italic leading-relaxed text-slate-600 sm:text-lg">
                &ldquo;Given the initial state of the machine and the input signals, it is always possible to predict all future behaviour.&rdquo;
            </blockquote>
            <p class="mt-3 text-xs font-medium text-slate-400">&mdash; Alan Turing</p>
        </div>
    </section>

</div>

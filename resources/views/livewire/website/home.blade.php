<div>
    {{-- Hero --}}
    @if($slides->isNotEmpty())
    <section class="relative h-[55vh] min-h-[420px] overflow-hidden" id="hero-slider">
        @foreach($slides as $i => $slide)
        <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }}">
            @if($slide->image_path)
                <img src="{{ Storage::url($slide->image_path) }}" alt="{{ $slide->title }}" class="absolute inset-0 w-full h-full object-cover">
            @else
                <div class="absolute inset-0 bg-slate-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/80 via-slate-900/50 to-transparent"></div>
            <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center pt-16">
                <div class="max-w-xl">
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-400 mb-3">
                        <span data-lang-key="school_name" data-en="Hulhudhuffaaru School">{{ __('school_name') }}</span>
                    </p>
                    <h1 class="text-4xl sm:text-5xl font-black text-white leading-tight mb-5">{{ $slide->title }}</h1>
                    @if($slide->description)
                        <p class="text-slate-300 text-base mb-8 leading-relaxed">{{ $slide->description }}</p>
                    @endif
                    @if($slide->button_1_label || $slide->button_2_label)
                    <div class="flex flex-wrap gap-3">
                        @if($slide->button_1_label)
                            <a href="{{ $slide->button_1_link_key ?: '#' }}" class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-6 py-3 rounded-xl transition-colors text-sm">{{ $slide->button_1_label }}</a>
                        @endif
                        @if($slide->button_2_label)
                            <a href="{{ $slide->button_2_link_key ?: '#' }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-6 py-3 rounded-xl border border-white/20 transition-colors text-sm">{{ $slide->button_2_label }}</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        @if($slides->count() > 1)
        {{-- Dots --}}
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            @foreach($slides as $i => $slide)
                <button onclick="heroGoTo({{ $i }})" class="hero-dot w-2 h-2 rounded-full transition-colors {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}"></button>
            @endforeach
        </div>
        {{-- Arrows --}}
        <button onclick="heroPrev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button onclick="heroNext()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 p-2 rounded-full bg-black/30 hover:bg-black/50 text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
        @endif
    </section>

    <script>
    (function() {
        var current = 0;
        var slides  = document.querySelectorAll('.hero-slide');
        var dots    = document.querySelectorAll('.hero-dot');
        var total   = slides.length;
        var timer;

        function goTo(n) {
            slides[current].classList.replace('opacity-100', 'opacity-0');
            slides[current].classList.add('pointer-events-none');
            if (dots[current]) dots[current].classList.replace('bg-white', 'bg-white/40');

            current = (n + total) % total;

            slides[current].classList.replace('opacity-0', 'opacity-100');
            slides[current].classList.remove('pointer-events-none');
            if (dots[current]) dots[current].classList.replace('bg-white/40', 'bg-white');
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        function startTimer() { timer = setInterval(next, 5000); }
        function resetTimer()  { clearInterval(timer); startTimer(); }

        window.heroGoTo = function(n) { goTo(n); resetTimer(); };
        window.heroNext = function()  { next();  resetTimer(); };
        window.heroPrev = function()  { prev();  resetTimer(); };

        if (total > 1) startTimer();
    })();
    </script>
    @endif
</div>

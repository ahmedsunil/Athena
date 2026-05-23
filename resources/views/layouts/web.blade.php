<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'dv' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'dv' ? 'locale-dv' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('school_name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        @php echo '@font-face'; @endphp {
            font-family: 'Dhivehi';
            src: url('{{ asset('fonts/Dhivehi.ttf') }}') format('truetype');
            font-display: swap;
        }
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }
        .locale-dv, .locale-dv * {
            font-family: 'Dhivehi', sans-serif !important;
            line-height: 2;
            word-spacing: 0.05em;
        }
        .locale-dv .lang-toggle,
        .locale-dv .lang-toggle * {
            font-family: 'DM Sans', system-ui, sans-serif !important;
            line-height: 1 !important;
            word-spacing: normal !important;
        }
        .brand-mark,
        .brand-mark * {
            line-height: 1.15 !important;
        }
        .brand-title,
        .brand-label {
            padding-block: 1px;
        }
        .locale-dv .brand-mark .brand-title {
            line-height: 1.45 !important;
        }
        .locale-dv .brand-mark .brand-label {
            line-height: 1.35 !important;
        }
        .site-header.home-transparent:not(.is-scrolled) {
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .brand-title,
        .site-header.home-transparent:not(.is-scrolled) .nav-link,
        .site-header.home-transparent:not(.is-scrolled) .nav-icon-button {
            color: rgba(255, 255, 255, 0.94) !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .brand-label {
            color: rgba(255, 255, 255, 0.68) !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .nav-link:hover,
        .site-header.home-transparent:not(.is-scrolled) .nav-icon-button:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.12) !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .lang-toggle {
            border-color: rgba(255, 255, 255, 0.25) !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .lang-button {
            background: rgba(255, 255, 255, 0.08) !important;
            color: rgba(255, 255, 255, 0.82) !important;
        }
        .site-header.home-transparent:not(.is-scrolled) .lang-button.is-active {
            background: #ffffff !important;
            color: #002366 !important;
        }
        [data-reveal] {
            opacity: 0;
            transform: translate3d(0, 22px, 0);
            transition:
                opacity 700ms cubic-bezier(0.22, 1, 0.36, 1),
                transform 700ms cubic-bezier(0.22, 1, 0.36, 1),
                filter 700ms cubic-bezier(0.22, 1, 0.36, 1);
            transition-delay: var(--reveal-delay, 0ms);
            will-change: opacity, transform;
        }
        [data-reveal="fade"] {
            transform: translate3d(0, 0, 0);
        }
        [data-reveal="left"] {
            transform: translate3d(-28px, 0, 0);
        }
        [data-reveal="right"] {
            transform: translate3d(28px, 0, 0);
        }
        [data-reveal="scale"] {
            transform: scale(0.96);
            filter: blur(3px);
        }
        [data-reveal].is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0) scale(1);
            filter: blur(0);
        }
        @media (prefers-reduced-motion: reduce) {
            [data-reveal] {
                opacity: 1;
                transform: none;
                filter: none;
                transition: none;
            }
        }
    </style>
    <script>
        window.__locale  = '{{ app()->getLocale() }}';
        window.__en      = {!! file_get_contents(base_path('lang/en.json')) !!};
        window.__dv      = {!! file_get_contents(base_path('lang/dv.json')) !!};
        window.__langUrl = '{{ url('/lang') }}';
    </script>
    @livewireStyles
</head>
<body class="website-shell font-sans bg-white text-slate-900 flex flex-col min-h-screen {{ request()->routeIs('home') ? '' : 'pt-16' }}">
@include('layouts.partials.nav')
@livewire('website.search')

<main class="flex-1">
    {{ $slot }}
</main>

@include('layouts.partials.footer')
@livewireScripts
<script>
    (function () {
        var observer = null;

        function setupReveal() {
            var revealItems = Array.prototype.slice.call(document.querySelectorAll('[data-reveal]:not(.is-visible)'));

            if (!revealItems.length) {
                return;
            }

            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
                revealItems.forEach(function (item) {
                    item.classList.add('is-visible');
                });
                return;
            }

            if (!observer) {
                observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    });
                }, {
                    rootMargin: '0px 0px -12% 0px',
                    threshold: 0.16
                });
            }

            revealItems.forEach(function (item) {
                observer.observe(item);
            });
        }

        document.addEventListener('DOMContentLoaded', setupReveal);
        document.addEventListener('livewire:navigated', setupReveal);
        new MutationObserver(function () {
            requestAnimationFrame(setupReveal);
        }).observe(document.body, { childList: true, subtree: true });

        document.addEventListener('livewire:init', function () {
            if (! window.Livewire || ! window.Livewire.hook) {
                return;
            }

            window.Livewire.hook('morph.updated', function () {
                requestAnimationFrame(setupReveal);
            });
        });
    })();

    (function () {
        function setupHeaderScroll() {
            var header = document.getElementById('site-header');
            if (!header || !header.classList.contains('home-transparent')) {
                return;
            }

            function syncHeader() {
                header.classList.toggle('is-scrolled', window.scrollY > 24);
            }

            syncHeader();
            window.addEventListener('scroll', syncHeader, { passive: true });
            document.addEventListener('livewire:navigated', syncHeader);
        }

        document.addEventListener('DOMContentLoaded', setupHeaderScroll);
        document.addEventListener('livewire:navigated', setupHeaderScroll);
    })();
</script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'dv' ? 'rtl' : 'ltr' }}" class="{{ app()->getLocale() === 'dv' ? 'locale-dv' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('school_name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['DM Sans', 'system-ui', 'sans-serif'],
                    mono: ['IBM Plex Mono', 'ui-monospace', 'monospace']
                }
            }
        }
    }</script>
    <style>
        @php echo '@font-face'; @endphp {
            font-family: 'Dhivehi';
            src: url('{{ asset('fonts/Dhivehi.ttf') }}') format('truetype');
            font-display: swap;
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
<body class="font-sans bg-white text-slate-900 flex flex-col min-h-screen pt-16">
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
</script>
</body>
</html>

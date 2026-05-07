<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'dv' ? 'rtl' : 'ltr' }}">
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
        .locale-dv [data-lang-key] {
            font-family: 'Dhivehi', sans-serif !important;
            line-height: 2;
            word-spacing: 0.05em;
        }
        @if(app()->getLocale() === 'dv')
        [data-lang-key] {
            font-family: 'Dhivehi', sans-serif !important;
            line-height: 2;
            word-spacing: 0.05em;
        }
        @endif
    </style>
    <script>
        window.__locale  = '{{ app()->getLocale() }}';
        window.__en      = {!! file_get_contents(base_path('lang/en.json')) !!};
        window.__dv      = {!! file_get_contents(base_path('lang/dv.json')) !!};
        window.__langUrl = '{{ url('/lang') }}';
    </script>
</head>
<body class="font-sans bg-white text-slate-900 flex flex-col min-h-screen">
@include('layouts.partials.nav')

<main class="flex-1">
    {{ $slot }}
</main>

@include('layouts.partials.footer')
</body>
</html>

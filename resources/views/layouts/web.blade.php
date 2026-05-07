<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hulhudhuffaaru School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
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
  @font-face {
    font-family: 'Dhivehi';
    src: url('{{ asset('fonts/Dhivehi.ttf') }}') format('truetype');
    font-display: swap;
  }
  .lang-dv [data-lang-key] { font-family: 'Dhivehi', sans-serif; }
</style>
<script>window.dvLang = {!! file_exists(base_path('lang/dv.json')) ? file_get_contents(base_path('lang/dv.json')) : '{}' !!};</script>
</head>
<body class="font-sans bg-white text-slate-900 flex flex-col min-h-screen">
@include('layouts.partials.nav')

<main class="flex-1">
    {{ $slot }}
</main>

@include('layouts.partials.footer')
</body>
</html>

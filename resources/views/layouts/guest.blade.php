<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-50 font-sans text-zinc-950 antialiased">

<div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">

    {{-- Brand --}}
    <div class="mb-8 text-center">
        <a href="/" class="inline-block">
            <span class="text-2xl font-bold tracking-tight text-zinc-950">{{ config('app.name') }}</span>
        </a>
    </div>

    {{-- Card --}}
    <div class="w-full max-w-sm">
        <div class="rounded-xl border border-zinc-200 bg-white p-8 shadow-sm">
            {{ $slot }}
        </div>
    </div>

</div>

@livewireScripts
</body>
</html>

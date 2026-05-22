<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-zinc-50 font-sans text-sm font-normal leading-5 text-zinc-950 antialiased">

<div class="flex h-full overflow-hidden">

    {{-- Desktop sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 flex w-64 shrink-0 flex-col border-r border-black bg-black text-white">
        @include('layouts.partials.sidebar')
    </aside>

    {{-- Main content --}}
    <div class="flex w-full flex-1 flex-col overflow-hidden pl-64">
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>

</div>

@livewireScripts

@if(session('toast'))
    <script>
        setTimeout(() => window.dispatchEvent(new CustomEvent('toast', {
            detail: {message: @js(session('toast')), type: @js(session('toast_type', 'success')) }
        })), 50);
    </script>
@endif

<x-admin.ui.toast />

</body>
</html>

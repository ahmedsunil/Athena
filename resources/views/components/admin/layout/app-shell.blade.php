{{--
    Layout: AppShell
    ----------------
    Full-page shell with fixed sidebar (desktop) and slide-in drawer (mobile).
    Wraps every authenticated admin page.

    Usage:
        <x-admin.layout.app-shell title="Page Title">
            {{ $slot }}
        </x-admin.layout.app-shell>

    Props:
        $title  (optional) — sets the <title> tag
--}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' — ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-zinc-50 font-sans text-zinc-900 antialiased">

<div class="flex h-full overflow-hidden">

    {{-- Desktop sidebar --}}
    <aside class="hidden w-56 shrink-0 flex-col border-r border-zinc-200 bg-white md:flex">
        @include('admin-system.partials.sidebar')
    </aside>

    {{-- Mobile topbar + drawer --}}
    <x-admin.layout.topbar />

    {{-- Main content area --}}
    <div class="flex flex-1 flex-col overflow-hidden pt-12 md:pt-0">

        {{-- Desktop breadcrumb --}}
        <x-admin.layout.breadcrumb />

        <main class="flex-1 overflow-y-auto">
            {{ $slot }}
        </main>
    </div>

</div>

@livewireScripts

{{-- Flash toast (post-redirect) --}}
@if(session('toast'))
    <script>
        setTimeout(() => window.dispatchEvent(new CustomEvent('toast', {
            detail: { message: @js(session('toast')), type: @js(session('toast_type', 'success')) }
        })), 50);
    </script>
@endif

{{-- Toast container --}}
<x-admin.ui.toast />

</body>
</html>

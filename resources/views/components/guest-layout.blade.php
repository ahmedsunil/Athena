<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' | ' . config('app.name') : config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300..700&family=IBM+Plex+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-zinc-50 font-sans text-sm font-normal leading-5 text-zinc-950 antialiased">

<div class="relative min-h-screen overflow-hidden">
    <div class="absolute inset-x-0 top-0 h-2 bg-black"></div>

    <main class="relative flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        <section class="w-full max-w-md">
            <div class="mb-8 text-center">
                <a href="/" class="inline-flex flex-col items-center">
                    <img src="{{ asset('images/app-mark.webp') }}" alt="{{ config('app.name') }}"
                         class="h-24 w-24 rounded-2xl object-contain shadow-sm">
                    <span class="mt-4 text-lg font-bold leading-6 text-zinc-950">{{ config('app.name') }}</span>
                    <span class="mt-1 text-xs font-normal leading-5 text-zinc-500">School website administration</span>
                </a>
            </div>

            <div class="rounded-lg border border-zinc-200 bg-white p-8 shadow-sm">
                {{ $slot }}
            </div>

            <p class="mt-6 text-center admin-caption">
                Secure access for authorized website administrators.
            </p>

            <p class="mt-2 text-center admin-caption">
                Developed by
                <a href="https://github.com/ahmedsunil" class="font-medium text-zinc-950 hover:text-zinc-700">
                    Ahmed Sunil
                </a>
            </p>
        </section>
    </main>
</div>

@livewireScripts

<x-admin.ui.toast />
</body>
</html>

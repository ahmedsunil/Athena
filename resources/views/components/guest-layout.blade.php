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

<div x-data="{
        toasts: [],
        show(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.dismiss(id), 3500);
        },
        dismiss(id) { this.toasts = this.toasts.filter(t => t.id !== id); }
     }"
     @toast.window="show($event.detail.message, $event.detail.type)"
     class="fixed bottom-4 right-4 z-[200] flex flex-col gap-2 pointer-events-none">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             :class="toast.type === 'error' ? 'bg-red-600' : 'bg-zinc-950'"
             class="flex min-w-[220px] items-center gap-2.5 rounded-lg px-4 py-2.5 admin-button-label text-white shadow-lg pointer-events-auto">
            <svg x-show="toast.type !== 'error'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            <svg x-show="toast.type === 'error'" class="h-4 w-4 shrink-0" fill="none" stroke="currentColor"
                 stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            <span x-text="toast.message" class="flex-1"></span>
            <button @click="dismiss(toast.id)" class="ml-auto opacity-70 hover:opacity-100">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>
</body>
</html>

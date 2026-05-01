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
    <aside class="fixed inset-y-0 left-0 z-30 hidden w-64 shrink-0 flex-col border-r border-black bg-black text-white md:flex">
        @include('layouts.partials.sidebar')
    </aside>

    {{-- Mobile topbar + drawer --}}
    <div
        class="fixed inset-x-0 top-0 z-40 flex h-12 items-center justify-between border-b border-zinc-200 bg-white px-4 md:hidden"
        x-data="{ open: false }"
        @keydown.escape.window="open = false">

        <span class="admin-section-title">{{ config('app.name') }}</span>

        <button @click="open = !open" class="rounded-lg p-1.5 text-zinc-500 transition-colors hover:bg-zinc-100">
            <svg x-show="!open" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg x-show="open" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-zinc-950/20 backdrop-blur-sm"
             @click="open = false"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-black bg-black text-white shadow-xl"
             @click.stop>
            <div class="flex h-12 items-center justify-between border-b border-white/10 px-4">
                <span class="text-[15px] font-bold tracking-tight text-white">{{ config('app.name') }}</span>
                <button @click="open = false"
                        class="rounded-lg p-1 text-white/70 transition-colors hover:bg-white/10 hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            @include('layouts.partials.sidebar')
        </div>
    </div>

    {{-- Main content --}}
    <div class="flex w-full flex-1 flex-col overflow-hidden pt-12 md:pl-64 md:pt-0">
        @php
            $breadcrumb = current_breadcrumb();
        @endphp
        <div class="hidden h-12 shrink-0 items-center border-b border-zinc-200 bg-white px-6 md:flex">
            <span class="admin-label-muted">{{ $breadcrumb }}</span>
        </div>

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

{{-- Toast notifications --}}
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
             class="flex min-w-[200px] items-center gap-2.5 rounded-lg px-4 py-2.5 admin-button-label text-white shadow-lg pointer-events-auto">
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

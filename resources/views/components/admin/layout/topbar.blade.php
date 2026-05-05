<div
    class="fixed inset-x-0 top-0 z-40 flex h-12 items-center justify-between border-b border-zinc-200 bg-white px-4 shadow-sm md:hidden"
    x-data="{ open: false }"
    @keydown.escape.window="open = false">

    {{-- App name / logo --}}
    <div class="flex items-center gap-2.5">
        <span class="text-sm font-semibold leading-5 text-zinc-950">{{ config('app.name') }}</span>
    </div>

    {{-- Hamburger / close toggle --}}
    <button @click="open = !open"
            class="rounded-lg border border-zinc-200 bg-white p-1.5 text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50">
        <svg x-show="!open" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg x-show="open" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-zinc-900/20 backdrop-blur-sm"
         @click="open = false">
    </div>

    {{-- Slide-in drawer --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 flex w-56 flex-col border-r border-zinc-200 bg-white shadow-lg"
         @click.stop>

        {{-- Drawer header --}}
        <div class="flex h-12 items-center justify-between border-b border-zinc-100 px-4">
            <span class="text-sm font-semibold leading-5 text-zinc-950">{{ config('app.name') }}</span>
            <button @click="open = false"
                    class="rounded-lg border border-zinc-200 bg-white p-1 text-zinc-400 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Shared sidebar content --}}
        @include('admin-system.components.layout.sidebar')
    </div>
</div>

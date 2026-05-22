<details class="fixed inset-x-0 top-0 z-40 border-b border-zinc-200 bg-white px-4 shadow-sm md:hidden">
    <summary class="flex h-12 cursor-pointer list-none items-center justify-between [&::-webkit-details-marker]:hidden">
        <span class="text-sm font-semibold leading-5 text-zinc-950">{{ config('app.name') }}</span>
        <span class="rounded-lg border border-zinc-200 bg-white p-1.5 text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50">
            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </span>
    </summary>

    <div class="fixed inset-y-0 left-0 z-50 flex w-56 flex-col border-r border-zinc-200 bg-white shadow-lg">
        <div class="flex h-12 items-center justify-between border-b border-zinc-100 px-4">
            <span class="text-sm font-semibold leading-5 text-zinc-950">{{ config('app.name') }}</span>
        </div>

        @include('layouts.partials.sidebar')
    </div>
</details>

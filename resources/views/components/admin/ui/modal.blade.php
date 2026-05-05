@props(['title', 'closeAction' => 'closeModal'])

<div class="fixed inset-0 z-50 flex items-center justify-center bg-zinc-900/30 backdrop-blur-sm"
     wire:click.self="{{ $closeAction }}">
    <div class="w-full max-w-sm rounded-2xl border border-zinc-200 bg-white p-6 shadow-lg ring-1 ring-zinc-100/80">

        <h2 class="admin-section-title mb-4">{{ $title }}</h2>

        <div>{{ $slot }}</div>

        @if(isset($footer))
            <div class="mt-4 flex gap-2">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

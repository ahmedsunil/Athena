<div class="mx-auto max-w-5xl space-y-4"
     x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'slides' }"
     x-init="$watch('tab', t => { const u = new URL(window.location); u.searchParams.set('tab', t); window.history.replaceState({}, '', u); })">

    <div>
        <h1 class="text-base font-semibold text-zinc-950">Home</h1>
        <p class="text-xs text-zinc-500">Manage all content shown on the public home page.</p>
    </div>

    {{-- Tab bar --}}
    <div class="flex gap-1 rounded-xl border border-zinc-200 bg-zinc-100 p-1">
        @foreach([
            ['key' => 'slides',        'label' => 'Slides'],
            ['key' => 'stats',         'label' => 'Stats'],
            ['key' => 'principal',     'label' => 'Principal'],
            ['key' => 'events',        'label' => 'Featured Events'],
            ['key' => 'links',         'label' => 'Quick Links'],
            ['key' => 'testimonials',  'label' => 'Testimonials'],
        ] as $t)
            <button type="button"
                    @click="tab = '{{ $t['key'] }}'"
                    :class="tab === '{{ $t['key'] }}' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950'"
                    class="flex-1 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                {{ $t['label'] }}
            </button>
        @endforeach
    </div>

    {{-- Slides tab --}}
    <div x-show="tab === 'slides'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Hero carousel slides.</p>
            <a href="{{ route('cms.slides.create') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Add Slide
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            @forelse($slides as $slide)
                <div class="flex items-center gap-4 border-b border-zinc-100 p-4 last:border-0">
                    @if($slide->image_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($slide->image_path) }}"
                             class="h-14 w-24 shrink-0 rounded-lg border border-zinc-200 object-cover" alt="">
                    @else
                        <div class="h-14 w-24 shrink-0 rounded-lg bg-zinc-100 flex items-center justify-center text-zinc-400 text-xs">No image</div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-950 truncate">{{ $slide->title }}</p>
                        <p class="text-xs text-zinc-500 truncate">{{ $slide->subtitle }}</p>
                        <p class="text-xs text-zinc-400">Order: {{ $slide->sort_order }} · {{ $slide->cta_label }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cms.slides.edit', $slide->id) }}"
                           class="inline-flex h-8 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
                        <button wire:click="deleteSlide({{ $slide->id }})" wire:confirm="Delete this slide?"
                                class="inline-flex h-8 items-center rounded-md border border-red-200 bg-white px-3 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-zinc-400">No slides yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Stats tab --}}
    <div x-show="tab === 'stats'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Bold highlight numbers.</p>
            <a href="{{ route('cms.stats.create') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Add Stat
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            @forelse($stats as $stat)
                <div class="flex items-center gap-4 border-b border-zinc-100 p-4 last:border-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-lg font-bold text-zinc-950">{{ $stat->value }}</p>
                        <p class="text-xs text-zinc-500">{{ $stat->label }}</p>
                        <p class="text-xs text-zinc-400">Order: {{ $stat->sort_order }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cms.stats.edit', $stat->id) }}"
                           class="inline-flex h-8 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
                        <button wire:click="deleteStat({{ $stat->id }})" wire:confirm="Delete this stat?"
                                class="inline-flex h-8 items-center rounded-md border border-red-200 bg-white px-3 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-zinc-400">No stats yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Principal tab --}}
    <div x-show="tab === 'principal'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Welcome message and profile.</p>
            <a href="{{ route('cms.principal.edit') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Edit Principal
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm p-5">
            @if($principal)
                <div class="flex items-start gap-4">
                    @if($principal->photo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($principal->photo_path) }}"
                             class="h-16 w-16 shrink-0 rounded-full border border-zinc-200 object-cover" alt="">
                    @else
                        <div class="h-16 w-16 shrink-0 rounded-full bg-zinc-100"></div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-zinc-950">{{ $principal->name }}</p>
                        <p class="text-xs text-zinc-500">{{ $principal->title }}</p>
                        <p class="mt-2 text-xs text-zinc-600 line-clamp-3">{{ $principal->message }}</p>
                    </div>
                </div>
            @else
                <p class="text-center text-sm text-zinc-400">No principal set yet.</p>
            @endif
        </div>
    </div>

    {{-- Featured Events tab --}}
    <div x-show="tab === 'events'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Upcoming events shown in the home strip.</p>
            <a href="{{ route('cms.featured-events.create') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Add Event
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            @forelse($events as $event)
                <div class="flex items-start gap-4 border-b border-zinc-100 p-4 last:border-0">
                    <div class="shrink-0 w-12 text-center">
                        <p class="text-sm font-bold text-zinc-950">{{ $event->date->format('d') }}</p>
                        <p class="text-xs text-zinc-400">{{ $event->date->format('M Y') }}</p>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-950">{{ $event->title }}</p>
                        <p class="text-xs text-zinc-500 truncate">{{ $event->description }}</p>
                        <p class="text-xs text-zinc-400">{{ $event->href }} · Order: {{ $event->sort_order }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cms.featured-events.edit', $event->id) }}"
                           class="inline-flex h-8 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
                        <button wire:click="deleteFeaturedEvent({{ $event->id }})" wire:confirm="Delete this event?"
                                class="inline-flex h-8 items-center rounded-md border border-red-200 bg-white px-3 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-zinc-400">No featured events yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Quick Links tab --}}
    <div x-show="tab === 'links'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Icon grid navigation buttons.</p>
            <a href="{{ route('cms.quick-links.create') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Add Link
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            @forelse($quickLinks as $link)
                <div class="flex items-center gap-4 border-b border-zinc-100 p-4 last:border-0">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-950">{{ $link->label }}</p>
                        <p class="text-xs text-zinc-500">{{ $link->href }}</p>
                        <p class="text-xs text-zinc-400">Icon: {{ $link->icon }} · Order: {{ $link->sort_order }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cms.quick-links.edit', $link->id) }}"
                           class="inline-flex h-8 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
                        <button wire:click="deleteQuickLink({{ $link->id }})" wire:confirm="Delete this link?"
                                class="inline-flex h-8 items-center rounded-md border border-red-200 bg-white px-3 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-zinc-400">No quick links yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Testimonials tab --}}
    <div x-show="tab === 'testimonials'" x-cloak>
        <div class="mb-3 flex items-center justify-between">
            <p class="text-xs text-zinc-500">Community quotes.</p>
            <a href="{{ route('cms.testimonials.create') }}"
               class="inline-flex h-8 items-center rounded-md bg-zinc-950 px-3 text-xs font-semibold text-white hover:bg-zinc-800">
                Add Testimonial
            </a>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
            @forelse($testimonials as $t)
                <div class="flex items-start gap-4 border-b border-zinc-100 p-4 last:border-0">
                    @if($t->photo_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($t->photo_path) }}"
                             class="h-10 w-10 shrink-0 rounded-full border border-zinc-200 object-cover" alt="">
                    @else
                        <div class="h-10 w-10 shrink-0 rounded-full bg-zinc-100"></div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-zinc-950">{{ $t->author }}</p>
                        <p class="text-xs text-zinc-500">{{ $t->role }}</p>
                        <p class="mt-1 text-xs text-zinc-600 line-clamp-2">{{ $t->quote }}</p>
                        <p class="text-xs text-zinc-400">Order: {{ $t->sort_order }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('cms.testimonials.edit', $t->id) }}"
                           class="inline-flex h-8 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 hover:bg-zinc-50">Edit</a>
                        <button wire:click="deleteTestimonial({{ $t->id }})" wire:confirm="Delete this testimonial?"
                                class="inline-flex h-8 items-center rounded-md border border-red-200 bg-white px-3 text-xs font-medium text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-sm text-zinc-400">No testimonials yet.</p>
            @endforelse
        </div>
    </div>

</div>

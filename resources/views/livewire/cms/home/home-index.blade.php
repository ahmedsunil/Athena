@php
    $inputClass = 'h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 disabled:bg-zinc-50 disabled:text-zinc-500 disabled:shadow-none';
    $textareaClass = 'w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 placeholder-zinc-400 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950 disabled:bg-zinc-50 disabled:text-zinc-500 disabled:shadow-none';
    $labelClass = 'mb-1.5 block text-xs font-medium text-zinc-700';
    $errorClass = 'mt-1 text-xs text-red-600';
@endphp

<div class="mx-auto space-y-4"
     x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'slides' }"
     x-init="$watch('tab', value => { const url = new URL(window.location); url.searchParams.set('tab', value); window.history.replaceState({}, '', url); })">

    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-base font-semibold text-zinc-950">Home</h1>
            <p class="text-xs text-zinc-500">View and manage the public home API content.</p>
        </div>

        <div class="flex items-center gap-2">
            @if($isEditing)
                <button type="button"
                        wire:click="cancelEdit"
                        class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-700 shadow-sm hover:bg-zinc-50">
                    Cancel
                </button>
                <button type="button"
                        wire:click="save"
                        wire:loading.attr="disabled"
                        wire:loading.class="cursor-not-allowed opacity-60"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-4 text-xs font-semibold text-white shadow-sm hover:bg-zinc-800 disabled:opacity-60">
                    Save Home
                </button>
            @else
                <button type="button"
                        wire:click="enableEdit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-4 text-xs font-semibold text-white shadow-sm hover:bg-zinc-800">
                    Enable Edit
                </button>
            @endif
        </div>
    </div>

    <div class="flex gap-1 overflow-x-auto rounded-xl border border-zinc-200 bg-zinc-100 p-1">
        @foreach([
            ['key' => 'slides', 'label' => 'Slides'],
            ['key' => 'stats', 'label' => 'Stats'],
            ['key' => 'principal', 'label' => 'Principal'],
            ['key' => 'events', 'label' => 'Featured Events'],
            ['key' => 'links', 'label' => 'Quick Links'],
            ['key' => 'testimonials', 'label' => 'Testimonials'],
            ['key' => 'contact', 'label' => 'Contact'],
        ] as $item)
            <button type="button"
                    @click="tab = '{{ $item['key'] }}'"
                    :class="tab === '{{ $item['key'] }}' ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950'"
                    class="shrink-0 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                {{ $item['label'] }}
            </button>
        @endforeach
    </div>

    <form wire:submit="save" class="space-y-4">
        <section x-show="tab === 'slides'" x-cloak class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-zinc-500">Hero carousel slides</p>
                @if($isEditing)
                    <button type="button" wire:click="addSlide"
                            class="text-xs font-semibold text-zinc-950 hover:text-zinc-600">Add Slide
                    </button>
                @endif
            </div>

            @forelse($slides as $index => $slide)
                <div wire:key="slide-{{ $index }}" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <p class="text-sm font-semibold text-zinc-950">Slide {{ $index + 1 }}</p>
                        @if($isEditing)
                            <button type="button" wire:click="removeSlide({{ $index }})"
                                    class="text-xs font-medium text-red-600 hover:text-red-700">Remove
                            </button>
                        @endif
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}">Image URL</label>
                            <input wire:model="slides.{{ $index }}.imageUrl"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("slides.$index.imageUrl") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Title</label>
                            <input wire:model="slides.{{ $index }}.title"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("slides.$index.title") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">CTA Label</label>
                            <input wire:model="slides.{{ $index }}.ctaLabel"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("slides.$index.ctaLabel") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Subtitle</label>
                            <textarea wire:model="slides.{{ $index }}.subtitle" rows="3"
                                      class="{{ $textareaClass }}" @disabled(!$isEditing)></textarea>
                            @error("slides.$index.subtitle") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">CTA Href</label>
                            <input wire:model="slides.{{ $index }}.ctaHref"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("slides.$index.ctaHref") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-zinc-200 bg-white p-6 text-center text-sm text-zinc-400">No slides
                    configured.</p>
            @endforelse
        </section>

        <section x-show="tab === 'stats'" x-cloak class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-zinc-500">Highlight statistics</p>
                @if($isEditing)
                    <button type="button" wire:click="addStat"
                            class="text-xs font-semibold text-zinc-950 hover:text-zinc-600">Add Stat
                    </button>
                @endif
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                @foreach($stats as $index => $stat)
                    <div wire:key="stat-{{ $index }}" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold text-zinc-950">Stat {{ $index + 1 }}</p>
                            @if($isEditing)
                                <button type="button" wire:click="removeStat({{ $index }})"
                                        class="text-xs font-medium text-red-600 hover:text-red-700">Remove
                                </button>
                            @endif
                        </div>
                        <div class="grid gap-3">
                            <div>
                                <label class="{{ $labelClass }}">Value</label>
                                <input wire:model="stats.{{ $index }}.value"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @error("stats.$index.value") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Label</label>
                                <input wire:model="stats.{{ $index }}.label"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @error("stats.$index.label") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section x-show="tab === 'principal'" x-cloak class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Name</label>
                    <input wire:model="principal.name" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('principal.name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Title</label>
                    <input wire:model="principal.title" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('principal.title') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $labelClass }}">Photo URL</label>
                    <input wire:model="principal.photoUrl" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('principal.photoUrl') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $labelClass }}">Message</label>
                    <textarea wire:model="principal.message" rows="7"
                              class="{{ $textareaClass }}" @disabled(!$isEditing)></textarea>
                    @error('principal.message') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section x-show="tab === 'events'" x-cloak class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-zinc-500">Choose Event records to show on the home page.</p>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white shadow-sm">
                @forelse($events as $event)
                    <label wire:key="event-option-{{ $event->public_id }}"
                           class="flex items-start gap-3 border-b border-zinc-100 p-4 last:border-0 {{ $isEditing ? 'cursor-pointer hover:bg-zinc-50' : '' }}">
                        <input type="checkbox"
                               wire:model="featuredEventIds"
                               value="{{ $event->public_id }}"
                               class="mt-1 h-4 w-4 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950"
                            @disabled(!$isEditing)>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-zinc-950">{{ $event->title }}</p>
                                <span
                                    class="rounded-full bg-zinc-100 px-2 py-0.5 text-[11px] font-medium text-zinc-500">{{ $event->status }}</span>
                            </div>
                            <p class="mt-1 text-xs text-zinc-500">
                                {{ $event->date_start?->format('Y-m-d') }}
                                @if($event->date_end && ! $event->date_end->equalTo($event->date_start))
                                    to {{ $event->date_end->format('Y-m-d') }}
                                @endif
                                · {{ $event->location }}
                            </p>
                            <p class="mt-1 line-clamp-2 text-xs text-zinc-600">{{ $event->short_description }}</p>
                        </div>
                    </label>
                @empty
                    <p class="p-6 text-center text-sm text-zinc-400">No events available.</p>
                @endforelse
            </div>

            @if($events->hasPages())
                <div
                    class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-zinc-200 bg-white px-4 py-3 shadow-sm">
                    <p class="text-xs text-zinc-500">
                        Showing {{ $events->firstItem() }}-{{ $events->lastItem() }} of {{ $events->total() }}
                    </p>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                wire:click="previousPage('eventsPage')"
                                @disabled($events->onFirstPage())
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950 disabled:pointer-events-none disabled:opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                            </svg>
                        </button>

                        @foreach($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                            <button type="button"
                                    wire:click="gotoPage({{ $page }}, 'eventsPage')"
                                    class="inline-flex h-8 min-w-8 items-center justify-center rounded-md border px-2 text-xs font-medium shadow-sm transition-colors {{ $events->currentPage() === $page ? 'border-zinc-950 bg-zinc-950 text-white' : 'border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50 hover:text-zinc-950' }}">
                                {{ $page }}
                            </button>
                        @endforeach

                        <button type="button"
                                wire:click="nextPage('eventsPage')"
                                @disabled(! $events->hasMorePages())
                                class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-zinc-200 bg-white text-zinc-500 shadow-sm transition-colors hover:bg-zinc-50 hover:text-zinc-950 disabled:pointer-events-none disabled:opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @error('featuredEventIds') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
            @error('featuredEventIds.*') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror

            @if(count($featuredEventIds) > 0)
                <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <p class="mb-3 text-xs font-medium text-zinc-500">Featured display order</p>
                    <div class="space-y-2">
                        @foreach($featuredEventIds as $index => $publicId)
                            @php $selectedEvent = $selectedEvents->get($publicId); @endphp
                            <div class="flex items-center justify-between rounded-lg bg-zinc-50 px-3 py-2">
                                <span
                                    class="text-xs font-medium text-zinc-700">{{ $index + 1 }}. {{ $selectedEvent?->title ?? $publicId }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <section x-show="tab === 'links'" x-cloak class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-zinc-500">Quick links</p>
                @if($isEditing)
                    <button type="button" wire:click="addQuickLink"
                            class="text-xs font-semibold text-zinc-950 hover:text-zinc-600">Add Link
                    </button>
                @endif
            </div>

            <div class="grid gap-3 md:grid-cols-2">
                @foreach($quickLinks as $index => $link)
                    <div wire:key="link-{{ $index }}" class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                        <div class="mb-3 flex items-center justify-between">
                            <p class="text-sm font-semibold text-zinc-950">Link {{ $index + 1 }}</p>
                            @if($isEditing)
                                <button type="button" wire:click="removeQuickLink({{ $index }})"
                                        class="text-xs font-medium text-red-600 hover:text-red-700">Remove
                                </button>
                            @endif
                        </div>
                        <div class="grid gap-3">
                            <div>
                                <label class="{{ $labelClass }}">Label</label>
                                <input wire:model="quickLinks.{{ $index }}.label"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @error("quickLinks.$index.label") <p
                                    class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Href</label>
                                <input wire:model="quickLinks.{{ $index }}.href"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @error("quickLinks.$index.href") <p
                                    class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="{{ $labelClass }}">Icon</label>
                                <input wire:model="quickLinks.{{ $index }}.icon"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @error("quickLinks.$index.icon") <p
                                    class="{{ $errorClass }}">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section x-show="tab === 'testimonials'" x-cloak class="space-y-3">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-zinc-500">Testimonials</p>
                @if($isEditing)
                    <button type="button" wire:click="addTestimonial"
                            class="text-xs font-semibold text-zinc-950 hover:text-zinc-600">Add Testimonial
                    </button>
                @endif
            </div>

            @foreach($testimonials as $index => $testimonial)
                <div wire:key="testimonial-{{ $index }}"
                     class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <div class="mb-3 flex items-center justify-between">
                        <p class="text-sm font-semibold text-zinc-950">Testimonial {{ $index + 1 }}</p>
                        @if($isEditing)
                            <button type="button" wire:click="removeTestimonial({{ $index }})"
                                    class="text-xs font-medium text-red-600 hover:text-red-700">Remove
                            </button>
                        @endif
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}">Photo URL</label>
                            <input wire:model="testimonials.{{ $index }}.photoUrl"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("testimonials.$index.photoUrl") <p
                                class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Author</label>
                            <input wire:model="testimonials.{{ $index }}.author"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("testimonials.$index.author") <p
                                class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Role</label>
                            <input wire:model="testimonials.{{ $index }}.role"
                                   class="{{ $inputClass }}" @disabled(!$isEditing)>
                            @error("testimonials.$index.role") <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Quote</label>
                            <textarea wire:model="testimonials.{{ $index }}.quote" rows="4"
                                      class="{{ $textareaClass }}" @disabled(!$isEditing)></textarea>
                            @error("testimonials.$index.quote") <p
                                class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

        <section x-show="tab === 'contact'" x-cloak class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="{{ $labelClass }}">Address</label>
                    <input wire:model="contact.address" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('contact.address') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Phone</label>
                    <input wire:model="contact.phone" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('contact.phone') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Email</label>
                    <input type="email" wire:model="contact.email" class="{{ $inputClass }}" @disabled(!$isEditing)>
                    @error('contact.email') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <div class="mb-2 flex items-center justify-between">
                        <label class="{{ $labelClass }} mb-0">Form Fields</label>
                        @if($isEditing)
                            <button type="button" wire:click="addContactField"
                                    class="text-xs font-semibold text-zinc-950 hover:text-zinc-600">Add Field
                            </button>
                        @endif
                    </div>
                    <div class="grid gap-2 sm:grid-cols-3">
                        @foreach(($contact['formFields'] ?? []) as $index => $field)
                            <div wire:key="contact-field-{{ $index }}" class="flex gap-2">
                                <input wire:model="contact.formFields.{{ $index }}"
                                       class="{{ $inputClass }}" @disabled(!$isEditing)>
                                @if($isEditing)
                                    <button type="button" wire:click="removeContactField({{ $index }})"
                                            class="h-9 rounded-md border border-red-200 px-2 text-xs font-medium text-red-600 hover:bg-red-50">
                                        Remove
                                    </button>
                                @endif
                            </div>
                            @error("contact.formFields.$index") <p
                                class="{{ $errorClass }}">{{ $message }}</p> @enderror
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    </form>
</div>

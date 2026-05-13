<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Events</h1>
        <p class="admin-muted">Manage school events shown on the public events page and home page.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit event' : 'Add event' }}</h3>
        </div>
        <form wire:submit="save" class="space-y-4">

            {{-- Title + Slug --}}
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title</label>
                    <input type="text" wire:model.live.debounce.400ms="title"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Slug</label>
                    <input type="text" wire:model="slug"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 font-mono text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('slug') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status + Dates --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Status</label>
                    <select wire:model="status"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                    @error('status') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Start date</label>
                    <input type="date" wire:model="date_start"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('date_start') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">End date <span class="text-zinc-400">(optional)</span></label>
                    <input type="date" wire:model="date_end"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('date_end') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Location --}}
            <div>
                <label class="mb-1.5 block admin-label">Location</label>
                <input type="text" wire:model="location"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('location') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Cover image --}}
            <div>
                <label class="mb-1.5 block admin-label">Cover image <span class="text-zinc-400">(optional)</span></label>
                @if($cover_image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ $cover_image->temporaryUrl() }}" alt="Preview" class="h-24 w-auto rounded-lg object-cover">
                        <button type="button" wire:click="removeCoverImage"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @elseif($existing_cover_image)
                    <div class="relative mb-2 inline-block">
                        <img src="{{ \App\Models\Event::resolveCoverImageUrl($existing_cover_image) }}" alt="Cover" class="h-24 w-auto rounded-lg object-cover">
                        <button type="button" wire:click="removeCoverImage"
                                class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-white shadow hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                @endif
                <input type="file" wire:model.live="cover_image" accept="image/*"
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                @error('cover_image') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Short description --}}
            <div>
                <label class="mb-1.5 block admin-label">Short description</label>
                <textarea wire:model="short_description" rows="2"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('short_description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Full description --}}
            <div>
                <label class="mb-1.5 block admin-label">Full description <span class="text-zinc-400">(optional, shown in modal)</span></label>
                <textarea wire:model="full_description" rows="4"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('full_description') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Contact --}}
            <div>
                <label class="mb-1.5 block admin-label">Contact <span class="text-zinc-400">(optional)</span></label>
                <input type="text" wire:model="contact" placeholder="e.g. events@school.edu.mv"
                       class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                @error('contact') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            {{-- Attachments --}}
            <div>
                <label class="mb-1.5 block admin-label">Attachments <span class="text-zinc-400">(optional)</span></label>
                <div class="space-y-2">
                    @foreach($attachments as $i => $attachment)
                        <div class="flex items-center gap-2">
                            <input type="text" wire:model="attachments.{{ $i }}.label" placeholder="Label"
                                   class="h-9 w-32 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <input type="url" wire:model="attachments.{{ $i }}.url" placeholder="https://..."
                                   class="h-9 flex-1 rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                            <button type="button" wire:click="removeAttachment({{ $i }})"
                                    class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-md border border-zinc-200 text-zinc-400 hover:border-red-200 hover:text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </div>
                        @error("attachments.{$i}.url") <p class="admin-form-error">{{ $message }}</p> @enderror
                    @endforeach
                </div>
                <button type="button" wire:click="addAttachment"
                        class="mt-2 inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add attachment
                </button>
            </div>

            {{-- Featured + Sort order + Active --}}
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" wire:model="is_featured" id="is_featured"
                           class="h-4 w-4 rounded border-zinc-300 text-zinc-950 focus:ring-zinc-950">
                    <label for="is_featured" class="admin-label cursor-pointer">Show on home page</label>
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Featured order</label>
                    <input type="number" wire:model="featured_sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Visibility</label>
                    <div class="grid h-9 grid-cols-2 rounded-md border border-zinc-200 bg-zinc-100 p-0.5 shadow-sm">
                        <button type="button" wire:click="$set('is_active', true)"
                                class="rounded-[5px] admin-link-label transition-colors {{ $is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Active</button>
                        <button type="button" wire:click="$set('is_active', false)"
                                class="rounded-[5px] admin-link-label transition-colors {{ !$is_active ? 'bg-white text-zinc-950 shadow-sm' : 'text-zinc-500 hover:text-zinc-950' }}">Inactive</button>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update event' : 'Add event' }}
                </button>
                @if($editingId)
                    <button type="button" wire:click="cancel"
                            class="inline-flex h-9 items-center rounded-md border border-zinc-200 bg-white px-3 admin-label shadow-sm transition-colors hover:bg-zinc-50">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Events list --}}
    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Cover</th>
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Status</th>
            <th class="admin-table-heading">Date</th>
            <th class="admin-table-heading">Featured</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($events as $event)
                <tr>
                    <td class="admin-table-cell">
                        @if($event->cover_image_path)
                            <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="h-10 w-16 rounded-md object-cover">
                        @else
                            <div class="h-10 w-16 rounded-md bg-zinc-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                        @endif
                    </td>
                    <td class="admin-table-cell-primary">{{ $event->title }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                            {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-600') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </td>
                    <td class="admin-table-cell text-zinc-500 text-xs whitespace-nowrap">{{ $event->date_start->format('j M Y') }}</td>
                    <td class="admin-table-cell">
                        @if($event->is_featured)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium bg-rose-50 text-rose-600">
                                #{{ $event->featured_sort_order + 1 }}
                            </span>
                        @else
                            <span class="text-zinc-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $event->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $event->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button wire:click="edit({{ $event->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button wire:click="delete({{ $event->id }})" wire:confirm="Delete this event?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No events yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @foreach($events as $event)
                <li class="flex items-center gap-3 px-4 py-3">
                    @if($event->cover_image_path)
                        <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="h-10 w-14 flex-shrink-0 rounded-md object-cover">
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $event->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ ucfirst($event->status) }} · {{ $event->date_start->format('j M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="edit({{ $event->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button wire:click="delete({{ $event->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @endforeach
        </x-slot>
    </x-admin.tables.data-table>

</div>

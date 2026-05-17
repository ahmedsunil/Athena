<div class="space-y-4">

    <div>
        <h1 class="admin-page-title">Announcements</h1>
        <p class="admin-muted">Publish public notices such as job openings, bids, competitions, and circulars.</p>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="mb-4">
            <h3 class="admin-section-title">{{ $editingId ? 'Edit announcement' : 'New announcement' }}</h3>
        </div>

        <form wire:submit="save" class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <label class="mb-1.5 block admin-label">Icon</label>
                    <select wire:model="icon_key"
                            class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                        @foreach($iconKeys as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('icon_key') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Category</label>
                    <input type="text" wire:model="category" placeholder="e.g. Job Opening, Bid, Competition"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('category') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Deadline <span class="text-zinc-400">(optional)</span></label>
                    <input type="date" wire:model="deadline"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('deadline') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Title (English)</label>
                    <input type="text" wire:model.live.debounce.400ms="title_en"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                    <label class="mt-2 mb-1.5 block admin-label">Title (ދިވެހި)</label>
                    <input type="text" wire:model="title_dv" dir="rtl"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('title_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-1.5 block admin-label">Slug</label>
                    <input type="text" wire:model="slug"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 font-mono text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('slug') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Description (English) <span class="text-zinc-400">(optional)</span></label>
                <textarea wire:model="description_en" rows="3"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_en') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
                <label class="mt-2 mb-1.5 block admin-label">Description (ދިވެހި)</label>
                <textarea wire:model="description_dv" rows="3" dir="rtl"
                          class="w-full rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950"></textarea>
                @error('description_dv') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block admin-label">Attached files <span class="text-zinc-400">(optional)</span></label>
                @if($attachments)
                    <div class="mb-3 space-y-2">
                        @foreach($attachments as $i => $attachment)
                            <div class="grid gap-2 rounded-md border border-zinc-200 bg-zinc-50 px-3 py-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.6fr)_auto] sm:items-center">
                                <input type="text" wire:model="attachments.{{ $i }}.label" placeholder="Label"
                                       class="h-8 rounded-md border border-zinc-200 bg-white px-2 text-xs text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                @if(! empty($attachment['path']))
                                    <input type="text" value="Uploaded file" disabled
                                           class="h-8 rounded-md border border-zinc-200 bg-zinc-100 px-2 text-xs text-zinc-400">
                                @else
                                    <input type="url" wire:model="attachments.{{ $i }}.url" placeholder="https://drive.google.com/..."
                                           class="h-8 rounded-md border border-zinc-200 bg-white px-2 text-xs text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                                @endif
                                <button type="button" wire:click="removeAttachment({{ $i }})"
                                        class="text-xs font-medium text-red-500 hover:text-red-700">Remove</button>
                            </div>
                            @error("attachments.{$i}.url") <p class="admin-form-error">{{ $message }}</p> @enderror
                        @endforeach
                    </div>
                @endif
                <input type="file" wire:model.live="uploaded_files" multiple
                       class="block w-full text-sm text-zinc-500 file:mr-3 file:rounded-md file:border-0 file:bg-zinc-100 file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-zinc-700 hover:file:bg-zinc-200">
                <button type="button" wire:click="addUrlAttachment"
                        class="mt-2 inline-flex h-8 items-center gap-1.5 rounded-md border border-zinc-200 bg-white px-3 text-xs font-medium text-zinc-600 shadow-sm hover:bg-zinc-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                    Add URL
                </button>
                @error('uploaded_files.*') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block admin-label">Sort order</label>
                    <input type="number" wire:model="sort_order" min="0"
                           class="h-9 w-full rounded-md border border-zinc-200 bg-white px-3 text-sm text-zinc-950 shadow-sm focus:border-zinc-950 focus:outline-none focus:ring-1 focus:ring-zinc-950">
                    @error('sort_order') <p class="mt-1 admin-form-error">{{ $message }}</p> @enderror
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

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex h-9 items-center rounded-md bg-zinc-950 px-3 admin-button-label text-white shadow-sm transition-colors hover:bg-zinc-800">
                    {{ $editingId ? 'Update announcement' : 'Add announcement' }}
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

    <x-admin.tables.data-table>
        <x-slot name="head">
            <th class="admin-table-heading">Title</th>
            <th class="admin-table-heading">Category</th>
            <th class="admin-table-heading">Created</th>
            <th class="admin-table-heading">Deadline</th>
            <th class="admin-table-heading">Files</th>
            <th class="admin-table-heading">Active</th>
            <th class="admin-table-heading"></th>
        </x-slot>
        <x-slot name="body">
            @forelse($announcements as $announcement)
                <tr>
                    <td class="admin-table-cell-primary">
                        {{ $announcement->title }}
                        @if($announcement->description)
                            <span class="block max-w-md truncate text-xs font-normal text-zinc-400">{{ Str::limit($announcement->description, 70) }}</span>
                        @endif
                    </td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ $announcement->category }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $announcement->created_at->format('j M Y') }}</td>
                    <td class="admin-table-cell whitespace-nowrap text-xs text-zinc-500">{{ $announcement->formatted_deadline ?? '—' }}</td>
                    <td class="admin-table-cell text-xs text-zinc-500">{{ count($announcement->attachments ?? []) }}</td>
                    <td class="admin-table-cell">
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $announcement->is_active ? 'bg-green-50 text-green-700' : 'bg-zinc-100 text-zinc-500' }}">
                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="admin-table-cell whitespace-nowrap text-right">
                        <button type="button" wire:click="edit({{ $announcement->id }})" class="mr-2 text-xs font-medium text-zinc-500 hover:text-zinc-950">Edit</button>
                        <button type="button" wire:click="delete({{ $announcement->id }})" wire:confirm="Delete this announcement?" class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="admin-table-cell text-center text-zinc-400">No announcements yet.</td>
                </tr>
            @endforelse
        </x-slot>
        <x-slot name="mobile">
            @forelse($announcements as $announcement)
                <li class="flex items-center gap-3 px-4 py-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-600">
                        <x-icon :key="$announcement->icon_key" class="h-4 w-4" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-zinc-950">{{ $announcement->title }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $announcement->category }} · {{ $announcement->created_at->format('j M Y') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" wire:click="edit({{ $announcement->id }})" class="text-xs text-zinc-500">Edit</button>
                        <button type="button" wire:click="delete({{ $announcement->id }})" wire:confirm="Delete?" class="text-xs text-red-500">Del</button>
                    </div>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-zinc-400">No announcements yet.</li>
            @endforelse
        </x-slot>
    </x-admin.tables.data-table>

</div>

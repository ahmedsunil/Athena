# Events Module Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a full Events module — `events` DB table + Model, CMS CRUD component, public `/events` listing page with modal details, and a Featured Events section on the home page.

**Architecture:** Follows the exact pattern of existing CMS modules (AchievementsIndex, HomeSlidesIndex): one Livewire component per module with inline list + form. Website page is a Livewire component under `layouts.web`. Home page gets a new section driven by `is_featured` flag.

**Tech Stack:** Laravel 11, Livewire 3, Tailwind CSS (via CDN in web layout), Alpine.js (for modal on events page)

---

## File Map

| Action | Path |
|---|---|
| Create | `database/migrations/2026_05_13_000001_create_events_table.php` |
| Create | `app/Models/Event.php` |
| Create | `app/Livewire/Cms/Events/EventsIndex.php` |
| Create | `resources/views/livewire/cms/events/events-index.blade.php` |
| Create | `app/Livewire/Website/Events.php` |
| Create | `resources/views/livewire/website/events.blade.php` |
| Modify | `routes/web.php` |
| Modify | `resources/views/layouts/partials/sidebar.blade.php` |
| Modify | `app/Livewire/Website/Home.php` |
| Modify | `resources/views/livewire/website/home.blade.php` |
| Create | `tests/Feature/EventsModuleTest.php` |

---

### Task 1: Migration + Model

**Files:**
- Create: `database/migrations/2026_05_13_000001_create_events_table.php`
- Create: `app/Models/Event.php`

- [ ] **Step 1: Create the migration**

Create `database/migrations/2026_05_13_000001_create_events_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->nullable()->unique();
            $table->enum('status', ['ongoing', 'upcoming', 'completed'])->default('upcoming');
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('date_start');
            $table->date('date_end')->nullable();
            $table->string('location');
            $table->string('cover_image_path')->nullable();
            $table->text('short_description');
            $table->longText('full_description')->nullable();
            $table->json('attachments')->nullable();
            $table->string('contact')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('featured_sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
```

- [ ] **Step 2: Run the migration**

```bash
php artisan migrate
```

Expected: `events` table created, no errors.

- [ ] **Step 3: Create the Event model**

Create `app/Models/Event.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'public_id', 'status', 'title', 'slug',
        'date_start', 'date_end', 'location',
        'cover_image_path', 'short_description', 'full_description',
        'attachments', 'contact',
        'is_featured', 'featured_sort_order', 'is_active',
    ];

    protected $casts = [
        'date_start'   => 'date',
        'date_end'     => 'date',
        'attachments'  => 'array',
        'is_featured'  => 'boolean',
        'is_active'    => 'boolean',
    ];

    public static function resolveCoverImageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return Storage::url($path);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return self::resolveCoverImageUrl($this->cover_image_path);
    }

    public function getFormattedDateRangeAttribute(): string
    {
        $start = $this->date_start->format('j M Y');
        if (! $this->date_end || $this->date_end->eq($this->date_start)) {
            return $start;
        }
        return $start . ' – ' . $this->date_end->format('j M Y');
    }
}
```

- [ ] **Step 4: Commit**

```bash
git add database/migrations/2026_05_13_000001_create_events_table.php app/Models/Event.php
git commit -m "feat: add events table migration and Event model"
```

---

### Task 2: CMS Livewire Component

**Files:**
- Create: `app/Livewire/Cms/Events/EventsIndex.php`

- [ ] **Step 1: Create the component**

Create `app/Livewire/Cms/Events/EventsIndex.php`:

```php
<?php

namespace App\Livewire\Cms\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class EventsIndex extends Component
{
    use WithFileUploads;

    public string $title = '';
    public string $slug = '';
    public string $status = 'upcoming';
    public string $date_start = '';
    public string $date_end = '';
    public string $location = '';
    public $cover_image = null;
    public ?string $existing_cover_image = null;
    public bool $coverImageRemoved = false;
    public string $short_description = '';
    public string $full_description = '';
    public string $contact = '';
    public array $attachments = [];
    public bool $is_featured = false;
    public int $featured_sort_order = 0;
    public bool $is_active = true;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return [
            'title'                => ['required', 'string', 'max:255'],
            'slug'                 => ['required', 'string', 'max:255'],
            'status'               => ['required', 'in:ongoing,upcoming,completed'],
            'date_start'           => ['required', 'date'],
            'date_end'             => ['nullable', 'date', 'after_or_equal:date_start'],
            'location'             => ['required', 'string', 'max:255'],
            'cover_image'          => ['nullable', 'image', 'max:4096'],
            'short_description'    => ['required', 'string'],
            'full_description'     => ['nullable', 'string'],
            'contact'              => ['nullable', 'string', 'max:255'],
            'attachments'          => ['nullable', 'array'],
            'attachments.*.label'  => ['nullable', 'string', 'max:255'],
            'attachments.*.url'    => ['nullable', 'url'],
            'is_featured'          => ['boolean'],
            'featured_sort_order'  => ['integer', 'min:0'],
            'is_active'            => ['boolean'],
        ];
    }

    public function updatedTitle(string $value): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title'               => $this->title,
            'slug'                => Str::slug($this->slug),
            'status'              => $this->status,
            'date_start'          => $this->date_start,
            'date_end'            => $this->date_end ?: null,
            'location'            => $this->location,
            'short_description'   => $this->short_description,
            'full_description'    => $this->full_description ?: null,
            'contact'             => $this->contact ?: null,
            'attachments'         => array_values(array_filter(
                $this->attachments,
                fn($a) => ! empty($a['label']) || ! empty($a['url'])
            )) ?: null,
            'is_featured'         => $this->is_featured,
            'featured_sort_order' => $this->featured_sort_order,
            'is_active'           => $this->is_active,
        ];

        if ($this->cover_image) {
            $data['cover_image_path'] = $this->cover_image->store('events', 'public');
        } elseif ($this->coverImageRemoved) {
            $data['cover_image_path'] = null;
        }

        if ($this->editingId) {
            Event::findOrFail($this->editingId)->update($data);
            $this->dispatch('toast', message: 'Event updated.');
        } else {
            Event::create($data);
            $this->dispatch('toast', message: 'Event created.');
        }

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $event = Event::findOrFail($id);
        $this->editingId            = $event->id;
        $this->title                = $event->title;
        $this->slug                 = $event->slug;
        $this->status               = $event->status;
        $this->date_start           = $event->date_start->format('Y-m-d');
        $this->date_end             = $event->date_end?->format('Y-m-d') ?? '';
        $this->location             = $event->location;
        $this->existing_cover_image = $event->cover_image_path;
        $this->coverImageRemoved    = false;
        $this->short_description    = $event->short_description;
        $this->full_description     = $event->full_description ?? '';
        $this->contact              = $event->contact ?? '';
        $this->attachments          = $event->attachments ?? [];
        $this->is_featured          = $event->is_featured;
        $this->featured_sort_order  = $event->featured_sort_order;
        $this->is_active            = $event->is_active;
    }

    public function delete(int $id): void
    {
        Event::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Event deleted.');
    }

    public function removeCoverImage(): void
    {
        $this->cover_image          = null;
        $this->existing_cover_image = null;
        $this->coverImageRemoved    = true;
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function addAttachment(): void
    {
        $this->attachments[] = ['label' => '', 'url' => ''];
    }

    public function removeAttachment(int $i): void
    {
        array_splice($this->attachments, $i, 1);
    }

    private function resetForm(): void
    {
        $this->reset([
            'title', 'slug', 'date_start', 'date_end', 'location',
            'cover_image', 'existing_cover_image', 'coverImageRemoved',
            'short_description', 'full_description', 'contact',
            'attachments', 'is_featured', 'featured_sort_order',
            'editingId',
        ]);
        $this->status    = 'upcoming';
        $this->is_active = true;
        $this->featured_sort_order = 0;
    }

    public function render()
    {
        return view('livewire.cms.events.events-index', [
            'events' => Event::orderByDesc('date_start')->orderBy('id')->get(),
        ])->layout('layouts.app', ['title' => 'Events']);
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Cms/Events/EventsIndex.php
git commit -m "feat: add CMS EventsIndex Livewire component"
```

---

### Task 3: CMS Blade View

**Files:**
- Create: `resources/views/livewire/cms/events/events-index.blade.php`

- [ ] **Step 1: Create the directory and view**

Create `resources/views/livewire/cms/events/events-index.blade.php`:

```blade
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
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/cms/events/events-index.blade.php
git commit -m "feat: add CMS events-index blade view"
```

---

### Task 4: Routes + Sidebar

**Files:**
- Modify: `routes/web.php`
- Modify: `resources/views/layouts/partials/sidebar.blade.php`

- [ ] **Step 1: Update routes/web.php**

Add import at top (with other CMS imports):

```php
use App\Livewire\Cms\Events\EventsIndex;
use App\Livewire\Website\Events;
```

Inside the `auth` middleware group, after the about routes, add:

```php
    // CMS — events module
    Route::get('/cms/events', EventsIndex::class)->name('cms.events.index');
```

Replace the placeholder website events route:

```php
// Replace: Route::get('/events', fn () => 'Events')->name('events');
// With:
Route::get('/events', Events::class)->name('events.index');
```

- [ ] **Step 2: Update sidebar.blade.php**

In `resources/views/layouts/partials/sidebar.blade.php`, in the `$navGroups` array, in the CMS group's `items` array, add the Events item **after** the About item:

```php
[
    'label'        => 'Events',
    'route'        => 'cms.events.index',
    'href'         => route('cms.events.index'),
    'activeRoutes' => ['cms.events.'],
    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>',
],
```

- [ ] **Step 3: Verify CMS route requires auth**

```bash
php artisan route:list --name=cms.events
```

Expected output includes `cms.events.index` with `auth,verified` middleware.

- [ ] **Step 4: Commit**

```bash
git add routes/web.php resources/views/layouts/partials/sidebar.blade.php
git commit -m "feat: register events CMS and website routes, add sidebar nav item"
```

---

### Task 5: Website Events Livewire Component

**Files:**
- Create: `app/Livewire/Website/Events.php`

- [ ] **Step 1: Create the component**

Create `app/Livewire/Website/Events.php`:

```php
<?php

namespace App\Livewire\Website;

use App\Models\Event;
use Livewire\Component;

class Events extends Component
{
    public string $filter = 'all';
    public ?int $selectedEventId = null;

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->selectedEventId = null;
    }

    public function selectEvent(int $id): void
    {
        $this->selectedEventId = $id;
    }

    public function closeModal(): void
    {
        $this->selectedEventId = null;
    }

    public function render()
    {
        $events = Event::where('is_active', true)
            ->when($this->filter !== 'all', fn ($q) => $q->where('status', $this->filter))
            ->orderByRaw("CASE WHEN status='ongoing' THEN 0 WHEN status='upcoming' THEN 1 ELSE 2 END")
            ->orderBy('date_start')
            ->get();

        $selectedEvent = $this->selectedEventId
            ? $events->firstWhere('id', $this->selectedEventId)
            : null;

        return view('livewire.website.events', [
            'events'        => $events,
            'selectedEvent' => $selectedEvent,
        ])->layout('layouts.web');
    }
}
```

- [ ] **Step 2: Commit**

```bash
git add app/Livewire/Website/Events.php
git commit -m "feat: add Website Events Livewire component"
```

---

### Task 6: Website Events Blade View

**Files:**
- Create: `resources/views/livewire/website/events.blade.php`

Design source: `docs/static_htmls/events.html` for cards and filter tabs. Modal is a Livewire-driven fixed overlay.

- [ ] **Step 1: Create the view**

Create `resources/views/livewire/website/events.blade.php`:

```blade
<div>

    {{-- Page header --}}
    <section class="bg-white border-b border-slate-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2" data-reveal="fade">School Events</p>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900" data-reveal="left">Events</h1>
        </div>
    </section>

    {{-- Filter tabs + grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Filter pills --}}
        <div class="flex gap-2 flex-wrap mb-8">
            @foreach(['all' => 'All', 'ongoing' => 'Ongoing', 'upcoming' => 'Upcoming', 'completed' => 'Completed'] as $value => $label)
                <button wire:click="setFilter('{{ $value }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition-colors
                               {{ $filter === $value
                                   ? 'bg-rose-600 border-rose-600 text-white'
                                   : 'bg-white border-slate-200 text-slate-600 hover:border-slate-300' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Events grid --}}
        @if($events->isEmpty())
            <div class="text-center py-20 text-slate-400">
                <p class="text-lg font-semibold">No {{ $filter !== 'all' ? $filter : '' }} events at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden hover:shadow-md transition-shadow" data-reveal="scale" style="--reveal-delay: {{ $loop->index * 60 }}ms">
                        {{-- Cover image --}}
                        <div class="relative h-48 overflow-hidden bg-slate-100">
                            @if($event->cover_image_path)
                                <img src="{{ $event->cover_image_url }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full
                                {{ $event->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($event->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600') }}">
                                {{ $event->status }}
                            </span>
                        </div>

                        {{-- Card body --}}
                        <div class="p-5">
                            <p class="text-xs text-sky-600 font-semibold mb-1">{{ $event->formatted_date_range }}</p>
                            <h3 class="font-bold text-slate-900 mb-1">{{ $event->title }}</h3>
                            <p class="text-xs text-slate-500 mb-3 flex items-start gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $event->location }}
                            </p>
                            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 mb-4">{{ $event->short_description }}</p>
                            <button wire:click="selectEvent({{ $event->id }})"
                                    class="block w-full text-center text-sm font-semibold text-rose-600 hover:text-rose-700 border border-rose-200 hover:border-rose-300 rounded-xl py-2 transition-colors">
                                View Details
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Modal overlay --}}
    @if($selectedEvent)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4 py-8" wire:click.self="closeModal">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

                {{-- Close button --}}
                <button wire:click="closeModal" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Cover image --}}
                @if($selectedEvent->cover_image_path)
                    <div class="h-56 overflow-hidden rounded-t-2xl">
                        <img src="{{ $selectedEvent->cover_image_url }}" alt="{{ $selectedEvent->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <div class="p-6 sm:p-8">
                    {{-- Status + date --}}
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs font-bold uppercase tracking-wide px-2.5 py-1 rounded-full
                            {{ $selectedEvent->status === 'ongoing' ? 'bg-emerald-100 text-emerald-700' : ($selectedEvent->status === 'upcoming' ? 'bg-sky-100 text-sky-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $selectedEvent->status }}
                        </span>
                        <span class="text-xs text-sky-600 font-semibold">{{ $selectedEvent->formatted_date_range }}</span>
                    </div>

                    {{-- Title --}}
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 mb-3">{{ $selectedEvent->title }}</h2>

                    {{-- Location --}}
                    <p class="text-sm text-slate-500 flex items-start gap-1.5 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $selectedEvent->location }}
                    </p>

                    {{-- Description --}}
                    <div class="text-sm text-slate-700 leading-relaxed mb-6 space-y-3">
                        @if($selectedEvent->full_description)
                            {!! nl2br(e($selectedEvent->full_description)) !!}
                        @else
                            {{ $selectedEvent->short_description }}
                        @endif
                    </div>

                    {{-- Attachments --}}
                    @if($selectedEvent->attachments && count($selectedEvent->attachments) > 0)
                        <div class="mb-5">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Attachments</p>
                            <div class="space-y-1.5">
                                @foreach($selectedEvent->attachments as $attachment)
                                    @if(!empty($attachment['url']))
                                        <a href="{{ $attachment['url'] }}" target="_blank" rel="noopener"
                                           class="flex items-center gap-2 text-sm text-rose-600 hover:text-rose-700 font-medium">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                            {{ $attachment['label'] ?: $attachment['url'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Contact --}}
                    @if($selectedEvent->contact)
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1">Contact</p>
                            <p class="text-sm text-slate-600">{{ $selectedEvent->contact }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/livewire/website/events.blade.php
git commit -m "feat: add website events blade view with modal"
```

---

### Task 7: Home Page — Featured Events Section

**Files:**
- Modify: `app/Livewire/Website/Home.php`
- Modify: `resources/views/livewire/website/home.blade.php`

- [ ] **Step 1: Add featuredEvents to Home component**

In `app/Livewire/Website/Home.php`, add `Event` import and `featuredEvents` to the render data array:

```php
use App\Models\Event;
```

In the `render()` return, add to the array passed to the view:

```php
'featuredEvents' => Event::where('is_featured', true)
    ->where('is_active', true)
    ->orderBy('featured_sort_order')
    ->take(3)
    ->get(),
```

The full render method becomes:

```php
public function render()
{
    $profile = SchoolProfile::singleton();

    return view('livewire.website.home', [
        'slides'         => HomeSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        'stats'          => HomeStat::where('is_active', true)->orderBy('sort_order')->get(),
        'quickAccess'    => HomeQuickAccess::where('is_active', true)->orderBy('sort_order')->get(),
        'featuredEvents' => Event::where('is_featured', true)
                                ->where('is_active', true)
                                ->orderBy('featured_sort_order')
                                ->take(3)
                                ->get(),
        'testimonials'   => HomeTestimonial::where('is_active', true)->orderBy('sort_order')->get(),
        'profile'        => $profile,
    ])->layout('layouts.web');
}
```

- [ ] **Step 2: Add Featured Events section to home blade**

In `resources/views/livewire/website/home.blade.php`, insert the following section **between** the `{{-- Quick Access --}}` section's closing `@endif` and the `{{-- Principal's Message --}}` section:

```blade
    {{-- Featured Events --}}
    @if($featuredEvents->isNotEmpty())
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10" data-reveal="fade">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-rose-600 mb-2">What's On</p>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900">Featured Events</h2>
                </div>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    All Events
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($featuredEvents as $event)
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200" data-reveal="scale" style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <p class="text-xs font-semibold text-sky-600 mb-2">{{ $event->date_start->format('j M Y') }}</p>
                    <h3 class="font-bold text-slate-900 mb-2">{{ $event->title }}</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $event->short_description }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
```

- [ ] **Step 3: Commit**

```bash
git add app/Livewire/Website/Home.php resources/views/livewire/website/home.blade.php
git commit -m "feat: add Featured Events section to home page"
```

---

### Task 8: Tests

**Files:**
- Create: `tests/Feature/EventsModuleTest.php`

- [ ] **Step 1: Write the tests**

Create `tests/Feature/EventsModuleTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventsModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_page_returns_ok(): void
    {
        $this->get('/events')->assertOk();
    }

    public function test_cms_events_route_requires_auth(): void
    {
        $this->get('/cms/events')->assertRedirect('/login');
    }

    public function test_events_page_shows_active_events(): void
    {
        Event::create([
            'title'             => 'Science Fair',
            'slug'              => 'science-fair',
            'status'            => 'upcoming',
            'date_start'        => '2025-06-01',
            'location'          => 'Main Hall',
            'short_description' => 'A science exhibition.',
            'is_active'         => true,
        ]);

        $this->get('/events')->assertSee('Science Fair');
    }

    public function test_events_page_hides_inactive_events(): void
    {
        Event::create([
            'title'             => 'Hidden Event',
            'slug'              => 'hidden-event',
            'status'            => 'upcoming',
            'date_start'        => '2025-06-01',
            'location'          => 'Main Hall',
            'short_description' => 'Not shown.',
            'is_active'         => false,
        ]);

        $this->get('/events')->assertDontSee('Hidden Event');
    }

    public function test_home_page_shows_featured_events(): void
    {
        Event::create([
            'title'               => 'Open Day',
            'slug'                => 'open-day',
            'status'              => 'upcoming',
            'date_start'          => '2025-05-10',
            'location'            => 'Campus',
            'short_description'   => 'Prospective families welcome.',
            'is_featured'         => true,
            'featured_sort_order' => 0,
            'is_active'           => true,
        ]);

        $this->get('/')->assertSee('Open Day');
    }

    public function test_home_page_hides_featured_section_when_no_featured_events(): void
    {
        $this->get('/')->assertDontSee('Featured Events');
    }
}
```

- [ ] **Step 2: Run the tests**

```bash
php artisan test tests/Feature/EventsModuleTest.php
```

Expected: 5 tests pass.

- [ ] **Step 3: Commit**

```bash
git add tests/Feature/EventsModuleTest.php
git commit -m "test: add EventsModule feature tests"
```

---

### Task 9: Seed + Verify

- [ ] **Step 1: Run the event seeder (if api_jsons/events.json exists)**

```bash
php artisan db:seed --class=EventSeeder
```

If `api_jsons/events.json` does not exist, the seeder silently returns — create a test event via the CMS instead.

- [ ] **Step 2: Verify CMS page loads**

Visit `/cms/events` (logged in). Expect: form + empty table, no errors.

- [ ] **Step 3: Create a test event via CMS**

Fill in the form: title, status "upcoming", start date, location, short description. Check "Show on home page". Save.

- [ ] **Step 4: Verify website pages**

- Visit `/events` — card grid shows event, filter pills work, "View Details" opens modal
- Visit `/` — Featured Events section shows between Quick Access and Principal's Message

- [ ] **Step 5: Final commit (if any cleanup)**

```bash
git add -p
git commit -m "chore: events module complete"
```

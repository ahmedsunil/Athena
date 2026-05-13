# Events Module Design

**Date:** 2026-05-13  
**Scope:** CMS events management + public events listing page + home page featured events section

---

## Overview

Three deliverables:

1. **CMS** — `EventsIndex` Livewire component at `/cms/events` for full CRUD
2. **Website** — `Website\Events` Livewire component at `/events` with filter tabs + modal detail view
3. **Home page** — Featured Events section injected between Quick Access and Principal's Message

---

## Data Model — `events` table

Migration: `2026_05_13_000001_create_events_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `public_id` | string unique nullable | seeder compatibility (e.g. `evt-001`) |
| `status` | enum `ongoing/upcoming/completed` | |
| `title` | string(255) | |
| `slug` | string unique | auto-generated from title, editable |
| `date_start` | date | |
| `date_end` | date nullable | |
| `location` | string(255) | |
| `cover_image_path` | string nullable | stored via `WithFileUploads` under `events/` disk |
| `short_description` | text | shown on cards |
| `full_description` | longtext nullable | shown in modal |
| `attachments` | json nullable | array of `{label: string, url: string}` |
| `contact` | string(255) nullable | contact info string |
| `is_featured` | boolean default false | drives home page section |
| `featured_sort_order` | int default 0 | order among featured events |
| `is_active` | boolean default true | hides from public if false |
| `timestamps` | | |

**Model:** `App\Models\Event`
- `$casts`: `date_start` → date, `date_end` → date, `attachments` → array, `is_featured` → bool, `is_active` → bool
- Accessor `cover_image_url`: returns `Storage::url($this->cover_image_path)` when set, else null

---

## CMS — EventsIndex

**Route:** `GET /cms/events` → `cms.events.index`  
**Class:** `App\Livewire\Cms\Events\EventsIndex`  
**View:** `resources/views/livewire/cms/events/events-index.blade.php`

### Sidebar

Add "Events" nav item to the CMS group in `sidebar.blade.php`, between About and User Management:

```php
[
    'label'        => 'Events',
    'route'        => 'cms.events.index',
    'href'         => route('cms.events.index'),
    'activeRoutes' => ['cms.events.'],
    'icon'         => '<path ... />', // calendar icon
],
```

### Component behaviour

Follows `AchievementsIndex` / `HomeSlidesIndex` pattern exactly:

- Public properties for all form fields
- `rules()` method returning validation array
- `save()`: validate → build data array → handle image upload/removal → `updateOrCreate` if `editingId` set else `create` → `dispatch('toast', ...)` → `resetForm()`
- `edit(int $id)`: populate all properties from model
- `delete(int $id)`: delete model, dispatch toast
- `removeImage()`: clear upload + set `imageRemoved = true`
- `cancel()`: call `resetForm()`
- `resetForm()`: `$this->reset(...)`, restore defaults
- `render()`: return view with `events` collection ordered by `date_start desc`, layout `layouts.app`

### Form fields

| Field | Input type | Notes |
|---|---|---|
| title | text | required |
| slug | text | required, unique; auto-filled from title via Livewire `updatedTitle()` hook (only when slug is currently empty) |
| status | select | ongoing / upcoming / completed |
| date_start | date | required |
| date_end | date | nullable |
| location | text | required |
| cover_image | file | nullable, image, max 4096KB; shows preview + remove button |
| short_description | textarea | required |
| full_description | textarea | nullable, larger |
| contact | text | nullable |
| attachments | repeater | add/remove rows of `{label, url}`; stored as JSON |
| is_featured | checkbox | |
| featured_sort_order | number | shown always; relevant when is_featured checked |
| is_active | checkbox | |

### Attachments repeater

Managed as a public array property `$attachments = []`. Methods:
- `addAttachment()`: push `['label' => '', 'url' => '']`
- `removeAttachment(int $i)`: `array_splice`

Validation rule: `attachments.*.label` nullable string, `attachments.*.url` nullable url.

### List view

Card/table rows showing: cover thumbnail, title, status badge, date range, is_featured toggle chip, is_active toggle, Edit / Delete actions.

---

## Website — Events Page

**Route:** `GET /events` → `events.index`  
**Class:** `App\Livewire\Website\Events`  
**View:** `resources/views/livewire/website/events.blade.php`  
**Layout:** `layouts.web`

### Filter tabs

Pills: Ongoing / Upcoming / Completed / All  
Active filter stored in public `$filter = 'all'` property.  
`setFilter(string $f)`: set property, Livewire re-renders.

Query: `Event::where('is_active', true)->when($filter !== 'all', fn($q) => $q->where('status', $filter))->orderByRaw("FIELD(status,'ongoing','upcoming','completed')")->orderBy('date_start')->get()`

### Event cards

Match `events.html` static prototype:
- Cover image (h-48, object-cover) with status badge overlay
- Date range, title, location (with info icon), short description (line-clamp-2)
- "View Details" button → opens modal

### Modal

Alpine.js slide-over panel (fixed right, `x-data` on the page, `x-show` + transition).  
`wire:click="openModal({{ $event->id }})"` on card button → sets `$selectedEventId` property → Livewire returns selected event data → dispatches browser event `open-event-modal` with payload → Alpine catches and shows panel.

Modal content: cover image (if set), status badge, full date, location, full_description, attachments list (links), contact.

No separate route per event (modal-only as confirmed).

---

## Home Page — Featured Events

**File:** `resources/views/livewire/website/home.blade.php`  
**Component:** `App\Livewire\Website\Home` (add `featuredEvents` to render data)

### Data

```php
'featuredEvents' => Event::where('is_featured', true)
    ->where('is_active', true)
    ->orderBy('featured_sort_order')
    ->take(3)
    ->get(),
```

### Section placement

Between `{{-- Quick Access --}}` and `{{-- Principal's Message --}}` sections.

### Section design

Matches `index.html` static prototype exactly:
- Label: "What's On" (rose-600 uppercase)
- Heading: "Featured Events"
- "All Events →" link (right-aligned, links to `route('events.index')`)
- 3-col grid, `bg-slate-50` cards: date (sky-600), title (bold), short_description
- Section hidden with `@if($featuredEvents->isNotEmpty())`

---

## Routes to add

```php
// CMS
use App\Livewire\Cms\Events\EventsIndex;
Route::get('/cms/events', EventsIndex::class)->name('cms.events.index');

// Website
use App\Livewire\Website\Events;
Route::get('/events', Events::class)->name('events.index');
```

The existing placeholder `Route::get('/events', fn () => 'Events')->name('events')` is replaced.

---

## Seeder

`EventSeeder` already written and references `App\Models\Event`. Run after migration with `php artisan db:seed --class=EventSeeder`. The seeder reads from `api_jsons/events.json` if present; if absent it silently returns — no dummy data needed separately.

---

## Files to create / modify

| Action | Path |
|---|---|
| Create | `database/migrations/2026_05_13_000001_create_events_table.php` |
| Create | `app/Models/Event.php` |
| Create | `app/Livewire/Cms/Events/EventsIndex.php` |
| Create | `resources/views/livewire/cms/events/events-index.blade.php` |
| Create | `app/Livewire/Website/Events.php` |
| Create | `resources/views/livewire/website/events.blade.php` |
| Modify | `app/Livewire/Website/Home.php` — add `featuredEvents` |
| Modify | `resources/views/livewire/website/home.blade.php` — add section |
| Modify | `routes/web.php` — add CMS + website routes, replace placeholder |
| Modify | `resources/views/layouts/partials/sidebar.blade.php` — add Events nav item |

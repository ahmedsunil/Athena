# HomeIndex

**Class:** `App\Livewire\Cms\Home\HomeIndex`  
**View:** `livewire.cms.home.home-index`  
**Route/page:** CMS → Home  
**DB table:** `home_pages` (single row, `id = 1`)

## Purpose

Manages all content for the public home page (`index.html`). Data is stored as a single JSON blob in `home_pages.payload`. Featured events are stored on the `events` table via `is_featured` / `featured_sort_order` flags.

---

## State properties

| Property | Type | Description |
|----------|------|-------------|
| `slides` | `array` | Hero carousel slides |
| `stats` | `array` | Stat strip items |
| `principal` | `array` | Principal profile & message |
| `featuredEventIds` | `array` | `public_id` values of featured events (sourced from DB, not payload) |
| `quickLinks` | `array` | Quick access link cards |
| `testimonials` | `array` | Community testimonial cards |
| `contact` | `array` | Contact info + form field names |
| `isEditing` | `bool` | Edit mode gate |
| `meta` | `array` | `_meta` block stored in payload |

---

## Payload structure (`home_pages.payload`)

```json
{
  "_meta": { ... },
  "slides": [
    { "id": "slide-1", "imageUrl": "", "title": "", "subtitle": "", "ctaLabel": "", "ctaHref": "" }
  ],
  "stats": [
    { "id": "stat-1", "value": "", "label": "" }
  ],
  "principal": {
    "name": "", "title": "", "photoUrl": "", "message": ""
  },
  "quickLinks": [
    { "id": "ql-1", "label": "", "href": "", "icon": "" }
  ],
  "testimonials": [
    { "id": "test-1", "quote": "", "author": "", "role": "", "photoUrl": "" }
  ],
  "contact": {
    "address": "", "phone": "", "email": "",
    "formFields": ["name", "email", "message"]
  }
}
```

> `featuredEvents` are **not** in the payload. They come from `events.is_featured = true`, ordered by `featured_sort_order`.

---

## Sections → index.html mapping

| CMS tab | index.html section |
|---------|--------------------|
| Slides | Hero carousel (`#hero-section`) |
| Stats | Rose stats strip |
| Principal | Dark principal quote block |
| Featured Events | "What's On" cards |
| Quick Links | "Quick Access" grid |
| Testimonials | "What People Say" grid |
| Contact | `#contact` section (email, phone, address) |

---

## Key methods

### `mount()`
Loads `HomePage::firstOrCreate(['id' => 1])`. Falls back to `api_jsons/home.json` if payload missing.

### `save()`
1. Wraps in DB transaction
2. Resets all `events.is_featured = false`
3. Sets `is_featured = true` + `featured_sort_order` for each `$featuredEventIds` entry
4. `HomePage::updateOrCreate(['id' => 1], ['payload' => ...])` 
5. Dispatches `toast` event

### `enableEdit()` / `cancelEdit()`
Toggle `$isEditing`. Cancel re-loads from DB and resets errors.

### Add/Remove methods
`addSlide`, `removeSlide`, `addStat`, `removeStat`, `addQuickLink`, `removeQuickLink`, `addTestimonial`, `removeTestimonial`, `addContactField`, `removeContactField` — all guard against `$isEditing`.

### ID generation
Each repeatable item gets a stable generated ID (`slide-1`, `stat-2`, etc.) via `withGeneratedIds()`. IDs persist across saves and are used as Livewire `wire:key` values.

---

## Default data

Falls back to `api_jsons/home.json` if `home_pages` row has no payload. That file contains sample slides, stats, principal, quick links, testimonials, and contact.

---

## Reading this data (other project, same DB)

```php
// home_pages row
$payload = json_decode(HomePage::first()->payload, true);

$slides      = $payload['slides'];
$stats       = $payload['stats'];
$principal   = $payload['principal'];
$quickLinks  = $payload['quickLinks'];
$testimonials = $payload['testimonials'];
$contact     = $payload['contact'];

// featured events (ordered)
$featuredEvents = Event::where('is_featured', true)
    ->orderBy('featured_sort_order')
    ->get();
```

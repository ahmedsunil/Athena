# Events Page CMS

> Covers both `events.html` (listing) and `event.html` (single detail).
> No external dependencies. Standalone phase.

---

## Models

### Event

A school event with a date range, location, and optional cover image.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| short_description | string | yes | shown in listing cards (2-3 lines) |
| full_description | longText | yes | shown on detail page (multi-paragraph) |
| cover_image_path | string | yes | |
| date_start | dateTime | no | |
| date_end | dateTime | yes | null = single-day event |
| location | string | yes | |
| status | enum | no | `upcoming`, `ongoing`, `completed` — auto-derived or manually set |
| contact_name | string | yes | person to contact about this event |
| contact_role | string | yes | |
| contact_email | string | yes | |
| contact_phone | string | yes | |
| is_active | boolean | no | default true |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### EventAttachment

Downloadable files attached to a specific event (e.g. programme, flyer, results).

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| event_id | unsignedBigInteger | no | FK → events |
| title | string | no | display name for the attachment |
| file_path | string | no | |
| file_type | string | no | e.g. "PDF", "DOCX" |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_events_table
php artisan make:migration create_event_attachments_table
```

### create_events_table
```php
$table->id();
$table->string('title');
$table->string('short_description')->nullable();
$table->longText('full_description')->nullable();
$table->string('cover_image_path')->nullable();
$table->dateTime('date_start');
$table->dateTime('date_end')->nullable();
$table->string('location')->nullable();
$table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
$table->string('contact_name')->nullable();
$table->string('contact_role')->nullable();
$table->string('contact_email')->nullable();
$table->string('contact_phone')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### create_event_attachments_table
```php
$table->id();
$table->foreignId('event_id')->constrained()->cascadeOnDelete();
$table->string('title');
$table->string('file_path');
$table->string('file_type');
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

---

## Relationships

### Event
```php
public function attachments(): HasMany
{
    return $this->hasMany(EventAttachment::class)->orderBy('sort_order');
}
```

### EventAttachment
```php
public function event(): BelongsTo
{
    return $this->belongsTo(Event::class);
}
```

---

## Scopes

```php
// On Event model
public function scopeFilter($query, string $status)
{
    if ($status !== 'all') {
        $query->where('status', $status);
    }
    return $query;
}
```

---

## Notes

- **Auto-derive `status`** — add a computed attribute or use a scheduled command to update `status` based on `date_start`/`date_end` vs `now()`:
  - `now() < date_start` → `upcoming`
  - `date_start <= now() <= date_end` → `ongoing`
  - `now() > date_end` (or `date_start` if no end) → `completed`
  - Alternatively: store manually in CMS and let admin control it.
- **Featured Events on home page** — `Event::where('is_active', true)->orderBy('date_start')->limit(3)->get()`
- `full_description` — stored as plain text with `\n\n` paragraph breaks. Render in frontend by splitting on double newlines.
- **Contact fields** are inline on `events` table (not a FK to `Staff`) — event contacts are often temporary or external people.
- `EventAttachment.file_type` — derive from extension on upload (same as Documents, see `06-downloads.md`).

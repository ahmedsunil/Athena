# Gallery Page CMS

> No external dependencies. Standalone phase.

---

## Models

### Album

A photo album shown as a card in the gallery grid.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| category | string | no | e.g. "Sports", "Academic", "Events" |
| cover_image_path | string | yes | thumbnail shown in grid |
| photo_count | unsignedInteger | no | default 0 — update when photos added |
| album_date | date | yes | date the album represents |
| facebook_url | string | yes | external Facebook album link |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_albums_table
```

### create_albums_table
```php
$table->id();
$table->string('title');
$table->string('category');
$table->string('cover_image_path')->nullable();
$table->unsignedInteger('photo_count')->default(0);
$table->date('album_date')->nullable();
$table->string('facebook_url')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

---

## Relationships

### Album
No relations in Phase 1. The gallery links out to Facebook for actual photos.

**Optional Phase 2 — local photo storage:**
```php
// Add if school wants photos stored locally instead of Facebook
public function photos(): MorphMany
{
    return $this->morphMany(Media::class, 'mediable')
                ->where('collection', 'gallery')
                ->orderBy('sort_order');
}
```
If adding local photos: remove `photo_count` column and derive dynamically with `$album->photos()->count()`.

---

## Notes

- **`category`** is free-text string (not enum) — derive distinct categories from `Album::distinct()->pluck('category')` for filter buttons.
- **Phase 1 scope**: Albums link to Facebook (`facebook_url`). No local photo management. `photo_count` is manually entered by admin.
- **Phase 2 extension**: Add local `AlbumPhoto` model (or use polymorphic `Media`) for schools that want to host photos themselves. Use `Media` from `00-shared-models.md`.
- `sort_order` defaults to newest-first via `orderByDesc('album_date')` — override with explicit `sort_order` if admin needs manual control.
- CMS UI for gallery: simple list with inline `cover_image_path` upload + `facebook_url` text field + `photo_count` number input.

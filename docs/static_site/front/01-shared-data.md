# Shared Frontend Data

Based on `cms/00-shared-models.md`.

These models are shared across multiple public pages. The frontend may define matching Eloquent models, but schema ownership stays in the CMS project.

## School Settings

Frontend usage:

- site title and meta defaults
- school name, motto, tagline
- founded year
- contact email, phone, address
- hero image fallback
- page overview copy such as `academics_overview`

Recommended service:

```php
final class SchoolSettings
{
    public function get(string $key, mixed $default = null): mixed
    {
        return cache()->remember("school-setting:{$key}", 3600, function () use ($key, $default) {
            return SchoolSetting::query()->where('key', $key)->value('value') ?? $default;
        });
    }

    public function group(string $group): array
    {
        return SchoolSetting::query()
            ->where('group', $group)
            ->pluck('value', 'key')
            ->all();
    }
}
```

Cache settings because every public page reads them.

## Staff

Frontend roles used by pages:

| Role | Public usage |
|---|---|
| `principal` | home and about principal message |
| `leadership` | about leadership team |
| `founding_teacher` | about founding teachers |
| patron references | student life clubs and scout groups |
| house master references | student life houses |

Query only active staff:

```php
Staff::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

## Media And Files

The CMS may store paths in direct columns such as `photo_path`, `cover_image_path`, and `file_path`, or use the shared polymorphic `media` table.

Frontend rule:

- Never render raw private storage paths.
- Convert storage paths with `Storage::disk('public')->url($path)` or through a media URL presenter.
- Use fallback images where content records do not have a cover image.
- Use `loading="lazy"` on non-hero images.

Recommended helper:

```php
function public_media_url(?string $path): ?string
{
    return $path ? Storage::disk('public')->url($path) : null;
}
```

## Shared Model Scopes

Most frontend reads should include:

```php
->where('is_active', true)
->orderBy('sort_order')
```

For dated content, prefer explicit date order:

```php
->orderBy('date_start')
```

or:

```php
->orderByDesc('published_at')
```

## Public Write Tables

The frontend may write to:

- `contact_submissions`
- `application_submissions`
- `lc_requests`

The frontend should not write to CMS content tables such as `slides`, `staff`, `events`, `documents`, `albums`, or `school_settings`.

## Frontend Presenters

Use presenters to normalize field names for views:

```php
[
    'title' => $event->title,
    'description' => $event->short_description,
    'href' => route('events.show', $event),
    'coverImageUrl' => public_media_url($event->cover_image_path),
]
```

This keeps the public UI stable even if the database columns evolve later.


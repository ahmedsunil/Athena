# Downloads Page CMS

> Depends on: `00-shared-models.md` (Media for file storage — optional, see Notes)

---

## Models

### Document

A downloadable file listed in the document library.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| category | string | no | e.g. "Academic", "Administrative", "Forms" |
| file_type | string | no | e.g. "PDF", "DOCX", "XLSX" |
| audience | enum | no | `all`, `students`, `parents`, `staff` |
| file_path | string | no | stored path on disk |
| file_size | unsignedBigInteger | yes | bytes — display as "2.4 MB" |
| published_at | date | no | |
| is_active | boolean | no | default true |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_documents_table
```

### create_documents_table
```php
$table->id();
$table->string('title');
$table->string('category');
$table->string('file_type');
$table->enum('audience', ['all', 'students', 'parents', 'staff'])->default('all');
$table->string('file_path');
$table->unsignedBigInteger('file_size')->nullable();
$table->date('published_at');
$table->boolean('is_active')->default(true);
$table->timestamps();
```

---

## Relationships

### Document
No relations. Self-contained uploadable record.

---

## Scopes

Add these query scopes to the `Document` model for common filters:

```php
// Filter by category
public function scopeInCategory($query, string $category)
{
    return $query->where('category', $category);
}

// Filter by audience (always include 'all')
public function scopeForAudience($query, string $audience)
{
    return $query->where(function ($q) use ($audience) {
        $q->where('audience', $audience)->orWhere('audience', 'all');
    });
}

// Search by title
public function scopeSearch($query, string $term)
{
    return $query->where('title', 'like', "%{$term}%");
}
```

---

## Notes

- **File storage** — use `Storage::disk('public')->put(...)` on upload. Store relative path in `file_path`. Serve via `Storage::url($document->file_path)`.
- **`file_type` detection** — derive from uploaded file extension on creation rather than asking admin to type it manually. Store uppercase: `strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION))`.
- **`file_size`** — store bytes, format for display with a helper: `Number::fileSize($document->file_size)` (Laravel 10+).
- **`category`** is a free-text string — not an enum — so admins can add new categories without a migration. Derive distinct category list from: `Document::distinct()->pluck('category')`.
- **Search** on the downloads page hits `title` only. Full-text search across other models is handled by `search.html` (no dedicated model).
- If using `Media` from `00-shared-models.md` instead of `file_path`: replace `file_path`, `file_type`, `file_size` columns with a `morphOne(Media::class, 'mediable')` relation and derive type/size from the `Media` record.

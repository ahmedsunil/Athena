# Gallery And Downloads Frontend

Based on `cms/08-gallery.md` and `cms/06-downloads.md`.

## Gallery

Route:

```text
GET /gallery
```

Livewire page:

```text
app/Livewire/Pages/Gallery.php
resources/views/livewire/pages/gallery.blade.php
```

### Sections

1. page header
2. category filters
3. album grid

### Query

```php
$albums = Album::query()
    ->where('is_active', true)
    ->when($this->category !== 'all', fn ($query) => $query->where('category', $this->category))
    ->orderBy('sort_order')
    ->orderByDesc('album_date')
    ->get();
```

### Album Card

Render:

- cover image
- title
- category
- photo count
- album date
- Facebook album link if present

Phase 1 links out to Facebook. Do not build a local photo lightbox unless the CMS adds local photo storage.

## Downloads

Route:

```text
GET /downloads
```

Livewire page:

```text
app/Livewire/Pages/Downloads.php
resources/views/livewire/pages/downloads.blade.php
```

### Filters

- search term
- category
- audience

### Query

```php
$documents = Document::query()
    ->where('is_active', true)
    ->when($this->category !== 'all', fn ($query) => $query->where('category', $this->category))
    ->when($this->audience !== 'all', function ($query) {
        $query->where(fn ($inner) => $inner
            ->where('audience', $this->audience)
            ->orWhere('audience', 'all'));
    })
    ->when($this->search !== '', fn ($query) => $query->where('title', 'like', "%{$this->search}%"))
    ->orderByDesc('published_at')
    ->get();
```

### Document Row

Render:

- title
- category
- audience
- file type
- formatted file size
- published date
- download/view link

Use `Number::fileSize($document->file_size)` where available.

## Empty States

- Gallery: show a message when no albums match the selected category.
- Downloads: show a message when no documents match the filters.

## SEO

Gallery title:

```text
Gallery | Hulhudhuffaaru School
```

Downloads title:

```text
Downloads | Hulhudhuffaaru School
```


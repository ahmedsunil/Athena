# Events Frontend

Based on `cms/07-events.md`.

Routes:

```text
GET /events
GET /events/{event}
```

Livewire pages:

```text
app/Livewire/Pages/Events/Index.php
app/Livewire/Pages/Events/Show.php
resources/views/livewire/pages/events/index.blade.php
resources/views/livewire/pages/events/show.blade.php
```

## Events Index

Sections:

1. page header
2. status filters
3. event cards
4. empty state

Filter state:

- `status = all | upcoming | ongoing | completed`

Query:

```php
$events = Event::query()
    ->where('is_active', true)
    ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
    ->orderBy('date_start')
    ->get();
```

Card fields:

- title
- short description
- cover image
- date range
- location
- status
- detail link

Use `wire:navigate` for detail links.

## Event Detail

Route model binding:

```php
public Event $event;
```

Query relation:

```php
$event->load(['attachments' => fn ($query) => $query->orderBy('sort_order')]);
```

Render:

- title
- cover image
- date range
- location
- full description split by paragraph breaks
- attachments
- contact panel if contact fields exist

## Attachments

Convert attachment `file_path` into a public URL or signed route. Do not render raw storage paths.

Display:

- title
- file type
- download/view link

## Status

If the CMS stores status manually, render the stored value. If the frontend computes display status, use date comparison only for labels and do not write back to the database.

## SEO

Index title:

```text
Events | Hulhudhuffaaru School
```

Detail title:

```text
{Event Title} | Hulhudhuffaaru School
```


# Academics Page Frontend

Based on `cms/03-academics.md`.

Route:

```text
GET /academics
```

Livewire page:

```text
app/Livewire/Pages/Academics.php
resources/views/livewire/pages/academics.blade.php
```

## Sections

1. academics overview from `SchoolSetting.academics_overview`
2. active levels ordered by `sort_order`
3. active courses grouped under each level
4. optional course category filters within each level

## Data Query

```php
$levels = Level::query()
    ->with(['courses' => fn ($query) => $query
        ->where('is_active', true)
        ->orderBy('sort_order')])
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

## UI Behavior

- Show level summary: label, abbreviation, age range, year groups, description.
- Show each course with category, duration, year group, short description, and optional expanded full description.
- Use Livewire state for expanded course IDs if cards expand/collapse.
- Keep course expansion accessible with real buttons.

## Empty States

- If no levels exist, show a short message that academic information is being updated.
- If a level has no active courses, render the level summary without an empty course grid.

## SEO

Title:

```text
Academics | Hulhudhuffaaru School
```

Description should explain the curriculum levels and learning approach.


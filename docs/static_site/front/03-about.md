# About Page Frontend

Based on `cms/02-about.md`.

Route:

```text
GET /about
```

Livewire page:

```text
app/Livewire/Pages/About.php
resources/views/livewire/pages/about.blade.php
```

## Sections

1. school identity header from `SchoolSetting`
2. mission and vision from `Mission`
3. history narrative from `HistorySection`
4. timeline from `TimelineEntry`
5. leadership team from `Staff`
6. founding teachers from `Staff`
7. achievements from `Achievement`

## Data Queries

```php
$mission = Mission::query()->first();

$historySections = HistorySection::query()
    ->orderBy('sort_order')
    ->get();

$timeline = TimelineEntry::query()
    ->orderBy('sort_order')
    ->orderBy('year')
    ->get();

$leadership = Staff::query()
    ->where('is_active', true)
    ->where('role', 'leadership')
    ->orderBy('sort_order')
    ->get();

$foundingTeachers = Staff::query()
    ->where('is_active', true)
    ->where('role', 'founding_teacher')
    ->orderBy('sort_order')
    ->get();

$achievements = Achievement::query()
    ->where('is_active', true)
    ->orderByDesc('year')
    ->get();
```

## History Body Blocks

`HistorySection.body` is JSON. Render supported block types only:

- `paragraph`
- `founderList`
- `highlightList`

Unknown block types should be ignored, not rendered raw.

## Achievement Filters

Use Livewire state if category or year filters are displayed.

State:

- `category = all | student | staff | school`
- `year = all | selected year`

Use URL query string persistence only if the filters are meant to be shareable.

## Empty States

- If `Mission` is missing, hide mission and vision cards.
- If no history sections exist, hide the history section.
- If no leadership records exist, hide leadership.
- If no achievements exist, hide filters and achievement grid.

## SEO

Title:

```text
About Hulhudhuffaaru School
```

Description should summarize the school history, mission, and community.


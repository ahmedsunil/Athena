# Student Life Frontend

Based on `cms/09-student-life.md`.

Route:

```text
GET /student-life
```

Livewire page:

```text
app/Livewire/Pages/StudentLife.php
resources/views/livewire/pages/student-life.blade.php
```

## Sections

1. student life overview
2. houses
3. clubs
4. prefects
5. scout groups

Tabs can be handled with Livewire state or simple anchor links. Use Livewire state if the selected tab should update without a page jump.

## Data Queries

```php
$clubs = Club::query()
    ->with('patron')
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$houses = House::query()
    ->with('houseMaster')
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$prefects = Prefect::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$scoutGroups = ScoutGroup::query()
    ->with('patron')
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

## Houses

`House.colour` stores a Tailwind color name. Do not interpolate arbitrary class names without an allowlist, because Tailwind needs to see class names at build time.

Recommended allowlist mapping:

```php
match ($house->colour) {
    'rose' => 'border-rose-500',
    'sky' => 'border-sky-500',
    'emerald' => 'border-emerald-500',
    'amber' => 'border-amber-500',
    default => 'border-stone-300',
};
```

## Clubs

Render:

- name
- meeting schedule
- description
- patron name and title if available
- president name and class if available

## Prefects

Render:

- name
- role
- class
- quote
- academic year

If academic year filters are shown, derive years from active prefect records.

## Scout Groups

Render:

- name
- group type
- meeting schedule
- description
- patron if available
- leader name if available

## SEO

Title:

```text
Student Life | Hulhudhuffaaru School
```

Description should mention houses, clubs, leadership, and student activities.


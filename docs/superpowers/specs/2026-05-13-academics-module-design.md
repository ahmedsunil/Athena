# Academics Module Design

**Date:** 2026-05-13
**Scope:** CMS academics management (overview + levels) + public academics listing page

---

## Overview

Two CMS components + one public website page:

1. **CMS Overview** — `AcademicsOverviewEdit` at `/cms/academics/overview` — singleton edit for intro text and curriculum line
2. **CMS Levels** — `AcademicLevelsIndex` at `/cms/academics/levels` — full CRUD for key stage cards
3. **Website** — `Website\Academics` at `/academics` — public page matching `docs/static_htmls/academics.html`

---

## Data Model

### `academics_overview` table

Migration: `2026_05_13_000002_create_academics_overview_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `text` | longText nullable | Overview paragraph |
| `curriculum` | string(255) nullable | e.g. "Maldives National Curriculum · Cambridge · Pearson Edexcel" |
| `timestamps` | | |

**Model:** `App\Models\AcademicsOverview`
- `singleton()`: `firstOrCreate(['id' => 1])`

### `academic_levels` table

Migration: `2026_05_13_000003_create_academic_levels_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `sort_order` | unsignedSmallInt default 0 | Display order |
| `abbreviation` | string(10) | FS, KS1, KS2, KS3, KS4, KS5 |
| `label` | string(255) | "Foundation Stage", "Key Stage 1", etc. |
| `age_range` | string(100) | "Ages 4 – 5" |
| `year_groups` | string(255) | "LKG – UKG", "Grade 1 – Grade 3", etc. |
| `lead_teacher` | string(255) | Teacher name |
| `lead_teacher_photo_path` | string nullable | Stored under `academics/` disk |
| `subjects` | json nullable | Array of strings |
| `targets` | json nullable | Array of strings |
| `streams` | json nullable | Array of strings (KS4/KS5 only) |
| `is_active` | boolean default true | |
| `timestamps` | | |

**Model:** `App\Models\AcademicLevel`
- `$casts`: `subjects` → array, `targets` → array, `streams` → array, `is_active` → boolean
- Accessor `lead_teacher_photo_url`: `Storage::url($path)` when set, else null

---

## CMS — AcademicsOverviewEdit

**Route:** `GET /cms/academics/overview` → `cms.academics.overview`
**Class:** `App\Livewire\Cms\Academics\AcademicsOverviewEdit`
**View:** `resources/views/livewire/cms/academics/overview-edit.blade.php`

### Behaviour

Follows `MissionEdit` pattern exactly:
- `mount()`: load singleton, populate `$text` and `$curriculum`
- `save()`: validate → `AcademicsOverview::singleton()->update([...])` → dispatch toast
- No reset needed (singleton, always editing)

### Form fields

| Field | Input | Notes |
|---|---|---|
| text | textarea rows=6 | nullable |
| curriculum | text | nullable, e.g. "Maldives National Curriculum · Cambridge · Pearson Edexcel" |

---

## CMS — AcademicLevelsIndex

**Route:** `GET /cms/academics/levels` → `cms.academics.levels`
**Class:** `App\Livewire\Cms\Academics\AcademicLevelsIndex`
**View:** `resources/views/livewire/cms/academics/levels-index.blade.php`

### Behaviour

Follows `AchievementsIndex` pattern exactly:
- Public properties for all form fields
- `rules()` method
- `save()`: validate → handle photo upload/removal → `updateOrCreate` or `create` → toast → `resetForm()`
- `edit(int $id)`: populate all properties
- `delete(int $id)`: delete model, toast
- `removePhoto()`: clear upload + set `photoRemoved = true`
- `cancel()`: `resetForm()`
- `addSubject()` / `removeSubject(int $i)`: manage `$subjects` array
- `addTarget()` / `removeTarget(int $i)`: manage `$targets` array
- `addStream()` / `removeStream(int $i)`: manage `$streams` array
- `resetForm()`: reset all, restore defaults (`is_active = true`, `sort_order = 0`)
- `render()`: `AcademicLevel::orderBy('sort_order')->orderBy('id')->get()`

### Form fields

| Field | Input | Notes |
|---|---|---|
| abbreviation | text | required, max 10 |
| label | text | required |
| age_range | text | required |
| year_groups | text | required |
| lead_teacher | text | required |
| lead_teacher_photo | file | nullable, image, max 2048KB |
| subjects | repeater | add/remove strings |
| targets | repeater | add/remove strings |
| streams | repeater | add/remove strings, optional |
| sort_order | number | min 0 |
| is_active | toggle | |

### List view

Table rows: abbreviation badge (colour-coded), label, age range, year groups, lead teacher name + avatar, active badge, Edit/Delete.

**Stage colours** (matching static HTML):
- FS → rose
- KS1 → sky
- KS2 → emerald
- KS3 → violet
- KS4 → amber
- KS5 → slate

---

## Website — Academics Page

**Route:** `GET /academics` → `academics.index` (replaces `fn () => 'Academics'` stub)
**Class:** `App\Livewire\Website\Academics`
**View:** `resources/views/livewire/website/academics.blade.php`
**Layout:** `layouts.web`

### Data

```php
public function render()
{
    return view('livewire.website.academics', [
        'overview' => AcademicsOverview::singleton(),
        'levels'   => AcademicLevel::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
    ])->layout('layouts.web');
}
```

### Page design (from `docs/static_htmls/academics.html`)

**Header section:**
- Label: "Our Curriculum" (rose-600 uppercase)
- Heading: "Academics"
- Overview text (from DB, if set)
- Curriculum badge (sky-100 text-sky-700, if set)
- Partner logo cards (Cambridge + Pearson Edexcel) — static images from `public/images/` — not CMS-managed

**Level cards grid** (3-col, `md:grid-cols-2 xl:grid-cols-3`):
- Abbreviation badge (stage colour)
- Label + age range
- Grades + lead teacher (initials avatar using stage colour)
- Streams (rose pills, if present)
- Subjects (slate tags)
- Targets (bullet list with rose dot)

---

## Routes to add

```php
// CMS — academics module
use App\Livewire\Cms\Academics\AcademicsOverviewEdit;
use App\Livewire\Cms\Academics\AcademicLevelsIndex;
Route::get('/cms/academics/overview', AcademicsOverviewEdit::class)->name('cms.academics.overview');
Route::get('/cms/academics/levels', AcademicLevelsIndex::class)->name('cms.academics.levels');

// Website
use App\Livewire\Website\Academics;
// Replace: Route::get('/academics', fn () => 'Academics')->name('academics');
Route::get('/academics', Academics::class)->name('academics.index');
```

---

## Sidebar

Add "Academics" nav item in the CMS group after Events:

```php
[
    'label'        => 'Academics',
    'route'        => 'cms.academics.overview',
    'href'         => route('cms.academics.overview'),
    'activeRoutes' => ['cms.academics.'],
    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/>',
],
```

---

## Files to create / modify

| Action | Path |
|---|---|
| Create | `database/migrations/2026_05_13_000002_create_academics_overview_table.php` |
| Create | `database/migrations/2026_05_13_000003_create_academic_levels_table.php` |
| Create | `app/Models/AcademicsOverview.php` |
| Create | `app/Models/AcademicLevel.php` |
| Create | `app/Livewire/Cms/Academics/AcademicsOverviewEdit.php` |
| Create | `resources/views/livewire/cms/academics/overview-edit.blade.php` |
| Create | `app/Livewire/Cms/Academics/AcademicLevelsIndex.php` |
| Create | `resources/views/livewire/cms/academics/levels-index.blade.php` |
| Create | `app/Livewire/Website/Academics.php` |
| Create | `resources/views/livewire/website/academics.blade.php` |
| Modify | `routes/web.php` |
| Modify | `resources/views/layouts/partials/sidebar.blade.php` |

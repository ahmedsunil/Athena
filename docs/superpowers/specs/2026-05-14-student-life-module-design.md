# Student Life Module Design

**Date:** 2026-05-14
**Scope:** CMS CRUD for 4 entities (Clubs, Prefects, Houses, Uniform Bodies) + public Student Life page matching `docs/static_htmls/student-life.html`

---

## Overview

Four CMS CRUD components + one public website page with tab navigation:

1. **CMS Clubs** — `ClubsIndex` at `/cms/student-life/clubs`
2. **CMS Prefects** — `PrefectsIndex` at `/cms/student-life/prefects`
3. **CMS Houses** — `HousesIndex` at `/cms/student-life/houses`
4. **CMS Uniform Bodies** — `UniformBodiesIndex` at `/cms/student-life/uniform-bodies`
5. **Website** — `Website\StudentLife` at `/student-life` — public page with 4 tabs

---

## Data Models

### `student_life_clubs` table

Migration: `2026_05_14_000001_create_student_life_clubs_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `sort_order` | unsignedSmallInt default 0 | |
| `name` | string(255) | |
| `description` | text nullable | |
| `meeting_schedule` | string(255) nullable | e.g. "Thursdays, 2:30 – 4:00 PM" |
| `patron_name` | string(255) nullable | |
| `patron_role` | string(255) nullable | e.g. "Physics Teacher" |
| `president_name` | string(255) nullable | |
| `president_class` | string(100) nullable | e.g. "Grade 10A" |
| `logo_path` | string nullable | stored under `student-life/clubs/` |
| `is_active` | boolean default true | |
| `timestamps` | | |

**Model:** `App\Models\StudentLifeClub`
- `logo_url` accessor: `Storage::url($path)` when set, else null
- `initials` accessor: first letter of each word in name, max 2

### `student_life_prefects` table

Migration: `2026_05_14_000002_create_student_life_prefects_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `sort_order` | unsignedSmallInt default 0 | |
| `name` | string(255) | |
| `photo_path` | string nullable | stored under `student-life/prefects/` |
| `role` | string(100) | e.g. "Head Boy", "Sports Prefect" |
| `class_name` | string(100) nullable | e.g. "Grade 11A" |
| `quote` | text nullable | |
| `is_active` | boolean default true | |
| `timestamps` | | |

**Model:** `App\Models\StudentLifePrefect`
- `photo_url` accessor: `Storage::url($path)` when set, else null
- `initials` accessor: first two capital initials of name
- `role_colour` accessor: maps role to Tailwind colour classes — Head Boy/Head Girl → rose, Senior Prefect → violet, Sports Prefect → emerald, Library Prefect → sky, Social Prefect → amber, Sanitation Prefect → teal, Cultural Prefect → orange; default → slate

### `student_life_houses` table

Migration: `2026_05_14_000003_create_student_life_houses_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `sort_order` | unsignedSmallInt default 0 | |
| `name` | string(255) | e.g. "Eagle House" |
| `colour` | string(20) | rose / sky / emerald / amber |
| `motto` | string(255) nullable | |
| `description` | text nullable | |
| `house_master_name` | string(255) nullable | |
| `house_master_role` | string(255) nullable | e.g. "House Master" |
| `captain_name` | string(255) nullable | |
| `captain_class` | string(100) nullable | |
| `is_active` | boolean default true | |
| `timestamps` | | |

**Model:** `App\Models\StudentLifeHouse`
- `colour_classes` accessor: maps colour → `['bg' => 'bg-rose-500', 'text' => 'text-rose-600', 'bg_light' => 'bg-rose-50']`

### `student_life_uniform_bodies` table

Migration: `2026_05_14_000004_create_student_life_uniform_bodies_table.php`

| Column | Type | Notes |
|---|---|---|
| `id` | bigint PK | |
| `sort_order` | unsignedSmallInt default 0 | |
| `name` | string(255) | e.g. "Boy Scouts Troop" |
| `group_type` | string(255) | free text — "Boy Scouts", "Girl Guides", "Little Maids", etc. |
| `colour` | string(20) | rose / sky / emerald / amber / violet / teal |
| `description` | text nullable | |
| `meeting_schedule` | string(255) nullable | |
| `patron_name` | string(255) nullable | |
| `patron_role` | string(255) nullable | |
| `leader_name` | string(255) nullable | |
| `leader_class` | string(100) nullable | |
| `logo_path` | string nullable | stored under `student-life/uniform-bodies/` |
| `is_active` | boolean default true | |
| `timestamps` | | |

**Model:** `App\Models\StudentLifeUniformBody`
- `logo_url` accessor: `Storage::url($path)` when set, else null
- `colour_classes` accessor: maps colour → bg/text/bg_light Tailwind classes

---

## CMS Components

All follow `AcademicLevelsIndex` / `AchievementsIndex` pattern exactly.

### Shared tab nav partial

**File:** `resources/views/layouts/partials/cms-student-life-tabs.blade.php`

Tabs: Clubs · Prefects · Houses · Uniform Bodies

### ClubsIndex

**Route:** `GET /cms/student-life/clubs` → `cms.student-life.clubs`
**Class:** `App\Livewire\Cms\StudentLife\ClubsIndex`
**View:** `resources/views/livewire/cms/student-life/clubs-index.blade.php`

Form fields: name (required), description (textarea), meeting_schedule, patron_name, patron_role, president_name, president_class, logo (file upload, nullable, image max 2048KB), sort_order, is_active toggle.

List: name, meeting schedule, patron, president, logo thumbnail or initials badge, active badge, Edit/Delete.

### PrefectsIndex

**Route:** `GET /cms/student-life/prefects` → `cms.student-life.prefects`
**Class:** `App\Livewire\Cms\StudentLife\PrefectsIndex`
**View:** `resources/views/livewire/cms/student-life/prefects-index.blade.php`

Form fields: name (required), role (required), class_name, quote (textarea), photo (file upload, nullable, image max 2048KB), sort_order, is_active toggle.

List: photo/initials avatar, name, role badge (role-coloured), class, Edit/Delete.

### HousesIndex

**Route:** `GET /cms/student-life/houses` → `cms.student-life.houses`
**Class:** `App\Livewire\Cms\StudentLife\HousesIndex`
**View:** `resources/views/livewire/cms/student-life/houses-index.blade.php`

Form fields: name (required), colour (select: rose/sky/emerald/amber, required), motto, description (textarea), house_master_name, house_master_role, captain_name, captain_class, sort_order, is_active toggle.

List: colour swatch dot, name, motto, house master, captain, active badge, Edit/Delete.

### UniformBodiesIndex

**Route:** `GET /cms/student-life/uniform-bodies` → `cms.student-life.uniform-bodies`
**Class:** `App\Livewire\Cms\StudentLife\UniformBodiesIndex`
**View:** `resources/views/livewire/cms/student-life/uniform-bodies-index.blade.php`

Form fields: name (required), group_type (required, free text), colour (select: rose/sky/emerald/amber/violet/teal, required), description (textarea), meeting_schedule, patron_name, patron_role, leader_name, leader_class, logo (file upload, nullable, image max 2048KB), sort_order, is_active toggle.

List: colour pill with group_type label, name, meeting schedule, patron, leader, Edit/Delete.

---

## Sidebar

Add after Academics:

```php
[
    'label'        => 'Student Life',
    'route'        => 'cms.student-life.clubs',
    'href'         => route('cms.student-life.clubs'),
    'activeRoutes' => ['cms.student-life.'],
    'icon'         => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>',
],
```

---

## Website — Student Life Page

**Route:** `GET /student-life` → `student-life.index`
**Class:** `App\Livewire\Website\StudentLife`
**View:** `resources/views/livewire/website/student-life.blade.php`
**Layout:** `layouts.web`

### Component

```php
public string $activeTab = 'clubs';

public function setTab(string $tab): void
{
    $this->activeTab = $tab;
}

public function render()
{
    return view('livewire.website.student-life', [
        'clubs'         => StudentLifeClub::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        'prefects'      => StudentLifePrefect::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        'houses'        => StudentLifeHouse::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
        'uniformBodies' => StudentLifeUniformBody::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get(),
    ])->layout('layouts.web');
}
```

### Page design (from `docs/static_htmls/student-life.html`)

**Header:** "Beyond the Classroom" label (rose-600), "Student Life" h1

**Sticky tab bar** (top-16, z-40): Clubs · Prefects · Houses · Uniform Bodies — active tab: rose-600 underline border

**Clubs tab** — 3-col grid:
- Card: logo image (if set) or initials badge (2-letter, colour auto-cycled by sort_order from palette: violet/pink/sky/amber/emerald/blue), club name, meeting schedule (sky-600 with calendar icon), description, footer: Teacher in charge + President

**Prefects tab** — 4-col grid:
- Card: photo or initials avatar (role-coloured ring-4), name, role badge (role-coloured), class, quote in italics

**Houses tab** — 2-col grid:
- Card: left colour bar (1.5 thick, house colour), shield SVG watermark (house colour, opacity-10), shield icon badge (house-coloured light bg), name, motto (italic, house colour), description, footer: House Master + Captain

**Uniform Bodies tab** — 3-col grid:
- Card: logo image (if set) or badge SVG in colour, group_type pill (colour-tinted), name, meeting schedule, description, footer: Patron + Leader

---

## Nav

Replace stub in `nav.blade.php`:
- `route('student-life')` → `route('student-life.index')`
- `routeIs('student-life')` → `routeIs('student-life*')`

---

## Seeder

`StudentLifeSeeder` — seeds all data from `docs/static_htmls/student-life.html`:
- 6 clubs (Science & Tech, Drama, Debate, Math, Green Earth, French)
- 8 prefects (Head Boy, Head Girl, Senior, Sports, Library, Social, Sanitation, Cultural)
- 4 houses (Eagle/rose, Lion/sky, Dolphin/emerald, Falcon/amber)
- 3 uniform bodies (Boy Scouts/emerald, Girls Brigade/rose, Red Cross/rose → update to teal for variety)

Added to `DatabaseSeeder::run()`.

---

## Routes

```php
// CMS — student life
use App\Livewire\Cms\StudentLife\ClubsIndex;
use App\Livewire\Cms\StudentLife\PrefectsIndex;
use App\Livewire\Cms\StudentLife\HousesIndex;
use App\Livewire\Cms\StudentLife\UniformBodiesIndex;
Route::get('/cms/student-life/clubs',          ClubsIndex::class)->name('cms.student-life.clubs');
Route::get('/cms/student-life/prefects',       PrefectsIndex::class)->name('cms.student-life.prefects');
Route::get('/cms/student-life/houses',         HousesIndex::class)->name('cms.student-life.houses');
Route::get('/cms/student-life/uniform-bodies', UniformBodiesIndex::class)->name('cms.student-life.uniform-bodies');

// Website
use App\Livewire\Website\StudentLife;
Route::get('/student-life', StudentLife::class)->name('student-life.index');
```

---

## Files

| Action | Path |
|---|---|
| Create | `database/migrations/2026_05_14_000001_create_student_life_clubs_table.php` |
| Create | `database/migrations/2026_05_14_000002_create_student_life_prefects_table.php` |
| Create | `database/migrations/2026_05_14_000003_create_student_life_houses_table.php` |
| Create | `database/migrations/2026_05_14_000004_create_student_life_uniform_bodies_table.php` |
| Create | `app/Models/StudentLifeClub.php` |
| Create | `app/Models/StudentLifePrefect.php` |
| Create | `app/Models/StudentLifeHouse.php` |
| Create | `app/Models/StudentLifeUniformBody.php` |
| Create | `app/Livewire/Cms/StudentLife/ClubsIndex.php` |
| Create | `app/Livewire/Cms/StudentLife/PrefectsIndex.php` |
| Create | `app/Livewire/Cms/StudentLife/HousesIndex.php` |
| Create | `app/Livewire/Cms/StudentLife/UniformBodiesIndex.php` |
| Create | `resources/views/livewire/cms/student-life/clubs-index.blade.php` |
| Create | `resources/views/livewire/cms/student-life/prefects-index.blade.php` |
| Create | `resources/views/livewire/cms/student-life/houses-index.blade.php` |
| Create | `resources/views/livewire/cms/student-life/uniform-bodies-index.blade.php` |
| Create | `resources/views/layouts/partials/cms-student-life-tabs.blade.php` |
| Create | `app/Livewire/Website/StudentLife.php` |
| Create | `resources/views/livewire/website/student-life.blade.php` |
| Modify | `routes/web.php` |
| Modify | `resources/views/layouts/partials/sidebar.blade.php` |
| Modify | `resources/views/layouts/partials/nav.blade.php` |
| Create | `database/seeders/StudentLifeSeeder.php` |
| Modify | `database/seeders/DatabaseSeeder.php` |
| Create | `tests/Feature/StudentLifeModuleTest.php` |

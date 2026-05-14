# Staff / Team Module — Design Spec

## Goal

Add a **Team** tab to the public About page showing the school's full organisational hierarchy (Principal → sections → sub-sections → individual staff), managed via a new CMS page under About → Team.

---

## Data Model

### Table: `staff_members`

| Column | Type | Constraints | Notes |
|---|---|---|---|
| `id` | bigint | PK | |
| `name` | string(255) | not null | |
| `designation` | string(255) | not null | e.g. "Head of Mathematics" |
| `education` | string(255) | nullable | e.g. "B.Sc Mathematics · M.Ed Curriculum" |
| `photo_path` | string(255) | nullable | path under `storage/app/public/staff-photos/` |
| `section` | enum | not null | `senior_management`, `academic`, `administrative` |
| `sub_section` | string(50) | nullable | null for Senior Management; see valid values below |
| `work_experiences` | json | not null, default `[]` | array of `{title, institution, period}` objects |
| `sort_order` | unsignedInteger | not null, default 0 | ordering within section/sub_section |
| `is_active` | boolean | not null, default true | |
| `created_at`, `updated_at` | timestamps | | |

**Valid `sub_section` values:**

- Section `academic`: `leading_teachers`, `teachers`, `academic_support`, `laboratory`, `library`
- Section `administrative`: `hr`, `budget`, `it`, `printer`
- Section `senior_management`: must be `null`

**`laboratory` and `library` are nested under `academic_support`** in the display hierarchy (they are stored as first-class sub_section values, not a separate nesting level).

### Model: `App\Models\StaffMember`

```php
protected $fillable = [
    'name', 'designation', 'education', 'photo_path',
    'section', 'sub_section', 'work_experiences',
    'sort_order', 'is_active',
];

protected $casts = [
    'work_experiences' => 'array',
    'is_active'        => 'boolean',
];
```

Photo URL accessor follows the same pattern as `LeadershipMember::resolvePhotoUrl()`.

---

## CMS

### Route

```
GET /cms/about/team  →  cms.about.team  →  App\Livewire\Cms\About\StaffIndex
```

Added to `resources/views/layouts/partials/cms-about-tabs.blade.php` after the Achievements entry.

### `App\Livewire\Cms\About\StaffIndex`

Uses `Livewire\WithFileUploads` trait.

**Component properties:**

| Property | Purpose |
|---|---|
| `$staffList` | Collection — all staff ordered by section, sort_order |
| `$showForm` | bool — form panel visible |
| `$editingId` | int\|null — null = new record |
| `$name`, `$designation`, `$education` | form fields |
| `$section` | enum string |
| `$subSection` | string\|null |
| `$sortOrder` | int |
| `$isActive` | bool |
| `$photo` | temporary upload |
| `$existingPhotoPath` | string\|null — current photo when editing |
| `$workExperiences` | array of `{title, institution, period}` |

**Methods:**

- `mount()` — loads `$staffList`
- `create()` — resets form, shows panel
- `edit(int $id)` — populates form from record, shows panel
- `save()` — validates, stores photo if uploaded, upserts record, refreshes list
- `delete(int $id)` — deletes record + photo file, refreshes list
- `cancel()` — hides form panel
- `addExperience()` — appends `['title'=>'','institution'=>'','period'=>'']` to `$workExperiences`
- `removeExperience(int $index)` — removes entry by index
- `updatedSection()` — clears `$subSection` when section changes

**Validation rules:**

```php
'name'           => 'required|string|max:255',
'designation'    => 'required|string|max:255',
'education'      => 'nullable|string|max:255',
'section'        => 'required|in:senior_management,academic,administrative',
'subSection'     => 'nullable|string',
'sortOrder'      => 'integer|min:0',
'photo'          => 'nullable|image|max:2048',
'workExperiences'            => 'array',
'workExperiences.*.title'    => 'required|string|max:255',
'workExperiences.*.institution' => 'required|string|max:255',
'workExperiences.*.period'   => 'required|string|max:100',
```

### View: `resources/views/livewire/cms/about/staff-index.blade.php`

Two-panel layout (same pattern as `achievements-index.blade.php`):

**Left panel — staff list:**
- Grouped display: Senior Management → Academic → Administrative
- Each row: circular avatar thumbnail (40 × 40) + name + designation + sub_section badge + edit button + delete button
- "Add staff member" button at top right

**Right panel — form (shown when `$showForm`):**
- Fields in order: Name, Designation, Education, Section (select), Sub-section (select, hidden when section = `senior_management`), Sort order, Active toggle, Photo upload (shows existing photo preview + "Remove" option), Work Experiences (dynamic rows)
- Work experience row: Job Title input · Institution input · Period input · Remove (×) button
- "Add experience" link below rows
- Save / Cancel buttons

Photo stored via `$photo->store('staff-photos', 'public')`. Deleted via `Storage::disk('public')->delete($path)` on record deletion.

---

## Website

### Tab addition

`resources/views/livewire/website/about.blade.php` — add **Team** button after Achievements in the tab nav:

```blade
<button wire:click="switchTab('team')" class="...">Team</button>
```

### `App\Livewire\Website\About` component

Add `$staff` property. In `mount()` (or lazy-loaded in `switchTab('team')`):

```php
$this->staff = StaffMember::where('is_active', true)
    ->orderBy('sort_order')
    ->get()
    ->groupBy('section');
```

Pass `$staff` to the view.

### Team tab content

**Structure (top to bottom):**

1. **Senior Management block** — always expanded, no toggle
   - Rose accent (`text-rose-600`, `border-rose-200`)
   - Horizontal wrapping grid of staff cards

2. **Academic Section block** — collapsible via Alpine `x-show`, collapsed by default
   - Sky accent (`text-sky-600`, `border-sky-200`)
   - Header: eyebrow "Academic Section" + "Principal as Team Lead"
   - Sub-sections rendered in order: `Leading Teachers` → `Teachers` → `Academic Support Staff` (which nests `Laboratory` and `Library` beneath it)

3. **Administrative Section block** — collapsible via Alpine `x-show`, collapsed by default
   - Amber accent (`text-amber-600`, `border-amber-200`)
   - Header: eyebrow "Administrative Section" + "Administrator as Team Lead"
   - Sub-sections: `HR` → `Budget` → `IT` → `Printer`

**Section header markup pattern:**

```html
<button @click="open = !open" class="w-full flex items-center justify-between ...">
  <div>
    <p class="text-xs font-bold uppercase tracking-widest text-{color}-600">Section label</p>
    <p class="text-lg font-black text-slate-900">Team Lead: Name</p>
  </div>
  <svg ... x-bind:class="open ? 'rotate-180' : ''" />  <!-- chevron -->
</button>
```

**Staff card:**

```
┌─────────────────────────────┐
│  [avatar 48×48]             │
│  Name (font-black)          │
│  Designation (text-sm)      │
└─────────────────────────────┘
  ↓ click toggles inline detail
┌─────────────────────────────┐
│  Education line             │
│  ── Work Experience ──      │
│  • Title · Institution      │
│    Period                   │
│  • ...                      │
└─────────────────────────────┘
```

Card: `bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer hover:shadow-md transition-shadow`

Avatar: circular, 48 × 48. If no photo: initials in a coloured circle (section colour).

Inline detail: `bg-slate-50 border-t border-slate-100 p-4 mt-2 rounded-b-xl` with `x-show` / `x-transition`.

Work experience entry: left border `border-l-2 border-{section-color}-300 pl-3`.

Cards laid out in `grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4` within each sub-section.

All cards and section blocks use `data-reveal="scale"` with staggered delays matching existing page patterns.

---

## File Map

| Action | Path |
|---|---|
| Create | `database/migrations/YYYY_MM_DD_create_staff_members_table.php` |
| Create | `app/Models/StaffMember.php` |
| Create | `app/Livewire/Cms/About/StaffIndex.php` |
| Create | `resources/views/livewire/cms/about/staff-index.blade.php` |
| Modify | `resources/views/layouts/partials/cms-about-tabs.blade.php` |
| Modify | `routes/web.php` (add `cms.about.team` route) |
| Modify | `app/Livewire/Website/About.php` (add staff loading) |
| Modify | `resources/views/livewire/website/about.blade.php` (add Team tab) |

---

## Out of Scope

- Staff reordering via drag-and-drop (use sort_order integer field)
- Search/filter within the Team tab
- Seeder (data entered via CMS)

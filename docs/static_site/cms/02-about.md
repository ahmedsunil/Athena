# About Page CMS

> Depends on: `00-shared-models.md` (Staff for Principal + Leadership + Founding Members)

---

## Models

### Mission

Single-row table storing the school's mission and vision statements.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| mission | text | no | |
| vision | text | no | |
| updated_at | timestamp | yes | |

> Only one row ever exists. Use `Mission::first()` or seed a single record.

---

### HistorySection

Sections within the school history narrative (e.g. "Our Roots", "Growth Era").

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| body | longText | no | stored as JSON array of block objects |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

**`body` block schema (JSON):**
```json
[
  { "type": "paragraph", "text": "..." },
  { "type": "founderList", "items": ["Name 1", "Name 2"] },
  { "type": "highlightList", "items": ["Highlight 1"] }
]
```

---

### TimelineEntry

Individual entries in the school history timeline.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| year | year | no | e.g. 1985 |
| title | string | no | |
| description | text | yes | |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Achievement

Student, staff, or school-wide achievement displayed on the Achievements tab.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| person_name | string | yes | null for school-wide achievements |
| category | enum | no | `student`, `staff`, `school` |
| year | year | no | |
| title | string | no | |
| description | text | yes | |
| award | string | yes | e.g. "Gold Medal" |
| event | string | yes | e.g. "National Science Fair" |
| photo_path | string | yes | |
| is_active | boolean | no | default true |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_mission_table
php artisan make:migration create_history_sections_table
php artisan make:migration create_timeline_entries_table
php artisan make:migration create_achievements_table
```

### create_mission_table
```php
$table->id();
$table->text('mission');
$table->text('vision');
$table->timestamps();
```

### create_history_sections_table
```php
$table->id();
$table->string('title');
$table->json('body'); // array of block objects
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_timeline_entries_table
```php
$table->id();
$table->year('year');
$table->string('title');
$table->text('description')->nullable();
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_achievements_table
```php
$table->id();
$table->string('person_name')->nullable();
$table->enum('category', ['student', 'staff', 'school']);
$table->year('year');
$table->string('title');
$table->text('description')->nullable();
$table->string('award')->nullable();
$table->string('event')->nullable();
$table->string('photo_path')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamps();
```

---

## Relationships

### Mission
No relations. Single-row config model.

### HistorySection
No foreign keys. Self-contained with JSON body blocks.

### TimelineEntry
No foreign keys. Self-contained chronological records.

### Achievement
No foreign keys. `person_name` is a plain string — not linked to `Staff` because achievements often reference external individuals or groups.

---

## Staff Usage on This Page

All staff data comes from the shared `Staff` model (see `00-shared-models.md`).

| About Page Section | Staff `role` value | Notes |
|---|---|---|
| Principal's Message | `principal` | `Staff::where('role','principal')->first()` |
| Leadership Team | `leadership` | `Staff::where('role','leadership')->orderBy('sort_order')->get()` |
| Founding Teachers | `founding_teacher` | `Staff::where('role','founding_teacher')->get()` |

---

## Notes

- `HistorySection.body` is a JSON column — cast it in the model: `protected $casts = ['body' => 'array'];`
- Achievement `year` filter in the UI maps to `Achievement::where('year', $year)->get()`
- Achievement `category` filter maps to `Achievement::where('category', $category)->get()`
- School name, motto, tagline, founded year all come from `SchoolSetting` (see `00-shared-models.md`)

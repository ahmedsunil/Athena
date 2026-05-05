# Student Life Page CMS

> Depends on: `00-shared-models.md` (Staff for patrons, house masters, scout leaders)

---

## Models

### Club

An extracurricular club shown in the Clubs tab.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | |
| meeting_schedule | string | yes | e.g. "Tuesdays, 3:00–4:30 PM" |
| description | text | yes | |
| patron_id | unsignedBigInteger | yes | FK → staff |
| president_name | string | yes | student name — not a Staff record |
| president_class | string | yes | e.g. "Year 12B" |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### House

A school house shown in the Houses tab.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | e.g. "Achimota House" |
| colour | string | no | Tailwind color name e.g. `rose`, `sky`, `emerald`, `amber` |
| motto | string | yes | |
| description | text | yes | |
| house_master_id | unsignedBigInteger | yes | FK → staff |
| captain_name | string | yes | student name |
| captain_class | string | yes | e.g. "Year 12A" |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Prefect

A student prefect shown in the Prefects tab.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | full name |
| role | string | no | e.g. "Head Prefect", "Social Prefect" |
| class | string | yes | e.g. "Year 12B" |
| quote | string | yes | short motto or quote |
| academic_year | string | yes | e.g. "2024/2025" — filter by year |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### ScoutGroup

A scout group (Boy Scouts, Girl Scouts, etc.) shown in the Scouts tab.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | e.g. "Holy Spirit Boy Scouts" |
| group_type | enum | no | `boy_scouts`, `girl_scouts`, `cubs`, `brownies` |
| meeting_schedule | string | yes | e.g. "Fridays, 2:00–4:00 PM" |
| description | text | yes | |
| patron_id | unsignedBigInteger | yes | FK → staff |
| leader_name | string | yes | student or staff leader name |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_clubs_table
php artisan make:migration create_houses_table
php artisan make:migration create_prefects_table
php artisan make:migration create_scout_groups_table
```

### create_clubs_table
```php
$table->id();
$table->string('name');
$table->string('meeting_schedule')->nullable();
$table->text('description')->nullable();
$table->foreignId('patron_id')->nullable()->constrained('staff')->nullOnDelete();
$table->string('president_name')->nullable();
$table->string('president_class')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_houses_table
```php
$table->id();
$table->string('name');
$table->string('colour');
$table->string('motto')->nullable();
$table->text('description')->nullable();
$table->foreignId('house_master_id')->nullable()->constrained('staff')->nullOnDelete();
$table->string('captain_name')->nullable();
$table->string('captain_class')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_prefects_table
```php
$table->id();
$table->string('name');
$table->string('role');
$table->string('class')->nullable();
$table->string('quote')->nullable();
$table->string('academic_year')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_scout_groups_table
```php
$table->id();
$table->string('name');
$table->enum('group_type', ['boy_scouts', 'girl_scouts', 'cubs', 'brownies']);
$table->string('meeting_schedule')->nullable();
$table->text('description')->nullable();
$table->foreignId('patron_id')->nullable()->constrained('staff')->nullOnDelete();
$table->string('leader_name')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

---

## Relationships

### Club
```php
public function patron(): BelongsTo
{
    return $this->belongsTo(Staff::class, 'patron_id');
}
```

### House
```php
public function houseMaster(): BelongsTo
{
    return $this->belongsTo(Staff::class, 'house_master_id');
}
```

### ScoutGroup
```php
public function patron(): BelongsTo
{
    return $this->belongsTo(Staff::class, 'patron_id');
}
```

### Prefect
No relations. Student data — not linked to `Staff`.

---

## Notes

- **Presidents and captains** are student names stored as plain strings — they change yearly and are not Staff records.
- **Patrons and house masters** are FK to `Staff` — these are teachers who hold the role semi-permanently.
- `Prefect.academic_year` allows the CMS to archive old prefect bodies. Active year: `Prefect::where('academic_year', '2024/2025')->get()`.
- `House.colour` stores a Tailwind color name (not a hex code) — the frontend uses it to render the color-coded top bar: `border-t-4 border-{colour}-500`.
- Query pattern for student life page:
  ```php
  Club::with('patron')->where('is_active', true)->orderBy('sort_order')->get();
  House::with('houseMaster')->where('is_active', true)->orderBy('sort_order')->get();
  Prefect::where('is_active', true)->orderBy('sort_order')->get();
  ScoutGroup::with('patron')->where('is_active', true)->orderBy('sort_order')->get();
  ```

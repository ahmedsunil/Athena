# Academics Page CMS

> No external dependencies. Standalone phase.

---

## Models

### Level

An academic level grouping courses (e.g. Primary, JHS, SHS).

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| label | string | no | e.g. "Primary School" |
| abbreviation | string | no | e.g. "PS", "JHS", "SHS" |
| age_range | string | yes | e.g. "6–11 years" |
| year_groups | string | yes | e.g. "Year 1 – Year 6" |
| description | text | yes | |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Course

A subject or course belonging to a Level.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| level_id | unsignedBigInteger | no | FK → levels |
| name | string | no | e.g. "Mathematics" |
| category | string | no | e.g. "Core", "Elective", "Science" |
| duration | string | yes | e.g. "3 years" |
| year_group | string | yes | e.g. "Year 7 – Year 9" |
| short_description | string | yes | one-liner shown in collapsed card |
| full_description | text | yes | shown when card is expanded |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_levels_table
php artisan make:migration create_courses_table
```

### create_levels_table
```php
$table->id();
$table->string('label');
$table->string('abbreviation');
$table->string('age_range')->nullable();
$table->string('year_groups')->nullable();
$table->text('description')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_courses_table
```php
$table->id();
$table->foreignId('level_id')->constrained()->cascadeOnDelete();
$table->string('name');
$table->string('category');
$table->string('duration')->nullable();
$table->string('year_group')->nullable();
$table->string('short_description')->nullable();
$table->text('full_description')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

---

## Relationships

### Level
```php
public function courses(): HasMany
{
    return $this->hasMany(Course::class)->orderBy('sort_order');
}
```

### Course
```php
public function level(): BelongsTo
{
    return $this->belongsTo(Level::class);
}
```

---

## Notes

- The academics page groups courses under their level. Query pattern:
  ```php
  Level::with(['courses' => fn($q) => $q->where('is_active', true)])
       ->where('is_active', true)
       ->orderBy('sort_order')
       ->get();
  ```
- `category` on `Course` is a free-text string (not an enum) — categories vary per level and school may add new ones without a migration.
- Overview text (intro paragraph at top of page) comes from `SchoolSetting` with key `academics_overview`.

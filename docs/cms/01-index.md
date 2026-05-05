# Home Page CMS

> Depends on: `00-shared-models.md` (Staff for Principal section)

---

## Models

### Slide

Hero carousel slides shown at the top of the home page.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| subtitle | string | yes | |
| image_path | string | no | |
| cta_label | string | yes | e.g. "Learn More" |
| cta_url | string | yes | |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Stat

School statistics displayed in the stats bar (e.g. "1,200 Students").

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| value | string | no | e.g. "1,200" or "98%" |
| label | string | no | e.g. "Students Enrolled" |
| icon | string | yes | icon name used by app.js |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### QuickLink

Navigation shortcut cards shown below the hero.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| label | string | no | |
| href | string | no | internal or external URL |
| icon | string | yes | icon name |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Testimonial

Student/parent/alumni testimonial cards.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| quote | text | no | |
| author_name | string | no | |
| author_role | string | yes | e.g. "Parent", "Alumni 2020" |
| photo_path | string | yes | |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### ContactSubmission

Stores messages submitted via the home page contact form.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | |
| email | string | no | |
| message | text | no | |
| is_read | boolean | no | default false |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_slides_table
php artisan make:migration create_stats_table
php artisan make:migration create_quick_links_table
php artisan make:migration create_testimonials_table
php artisan make:migration create_contact_submissions_table
```

### create_slides_table
```php
$table->id();
$table->string('title');
$table->string('subtitle')->nullable();
$table->string('image_path');
$table->string('cta_label')->nullable();
$table->string('cta_url')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_stats_table
```php
$table->id();
$table->string('value');
$table->string('label');
$table->string('icon')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_quick_links_table
```php
$table->id();
$table->string('label');
$table->string('href');
$table->string('icon')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_testimonials_table
```php
$table->id();
$table->text('quote');
$table->string('author_name');
$table->string('author_role')->nullable();
$table->string('photo_path')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_contact_submissions_table
```php
$table->id();
$table->string('name');
$table->string('email');
$table->text('message');
$table->boolean('is_read')->default(false);
$table->timestamps();
```

---

## Relationships

### Slide
No relations. Standalone sortable content.

### Stat
No relations. Standalone sortable content.

### QuickLink
No relations. Standalone sortable content.

### Testimonial
No relations. Standalone sortable content.

### ContactSubmission
No relations. Read-only inbox in the CMS.

---

## Notes

- **Principal's Message** on the home page pulls from `Staff` where `role = 'principal'` (see `00-shared-models.md`). No separate model needed.
- **Featured Events** on the home page pulls the 3 most recent active `Event` records (see `07-events.md`). No separate model needed.
- `ContactSubmission` has no reply functionality in scope — it is an inbox only. Add email notification via `Mail` listener on creation.
- `sort_order` on Slides, Stats, QuickLinks, Testimonials can be managed via a drag-and-drop CMS list using Spatie's `spatie/eloquent-sortable` package.

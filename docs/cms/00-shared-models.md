# Shared Models CMS

> Build these first. All other phases depend on them.

---

## Models

### SchoolSetting

Key/value store for global school configuration (name, motto, contact, social links).

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| key | string | no | unique, e.g. `school_name`, `contact_email` |
| value | text | yes | |
| group | string | no | default `general` — groups keys in CMS UI |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

**Predefined keys:**

| Key | Group | Example |
|-----|-------|---------|
| school_name | general | Holy Spirit School |
| school_motto | general | Excellence in Education |
| school_tagline | general | Nurturing Minds, Building Futures |
| founded_year | general | 1985 |
| contact_email | contact | info@holyspirit.edu |
| contact_phone | contact | +233 XX XXX XXXX |
| contact_address | contact | 123 School Road, Accra |
| hero_image_url | appearance | /images/hero.jpg |

---

### Staff

Reusable person model. Used for: principal, leadership team, founding teachers, club patrons, house masters, scout leaders.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| name | string | no | |
| title | string | no | e.g. "Principal", "Head of Science" |
| role | string | yes | e.g. "Leadership", "Patron", "Founding Teacher" |
| department | string | yes | |
| photo_path | string | yes | stored in `public/storage` |
| bio | text | yes | |
| email | string | yes | |
| phone | string | yes | |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 — controls display order |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### Media

Polymorphic file/image attachment. Reused across Events, Documents, Albums, Staff photos.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| mediable_id | unsignedBigInteger | no | polymorphic FK |
| mediable_type | string | no | polymorphic model class |
| collection | string | no | default `default` — e.g. `attachments`, `cover`, `gallery` |
| disk | string | no | default `public` |
| path | string | no | relative path on disk |
| original_name | string | yes | original filename on upload |
| mime_type | string | yes | e.g. `image/jpeg`, `application/pdf` |
| size | unsignedBigInteger | yes | bytes |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_school_settings_table
php artisan make:migration create_staff_table
php artisan make:migration create_media_table
```

### create_school_settings_table
```php
$table->id();
$table->string('key')->unique();
$table->text('value')->nullable();
$table->string('group')->default('general');
$table->timestamps();
```

### create_staff_table
```php
$table->id();
$table->string('name');
$table->string('title');
$table->string('role')->nullable();
$table->string('department')->nullable();
$table->string('photo_path')->nullable();
$table->text('bio')->nullable();
$table->string('email')->nullable();
$table->string('phone')->nullable();
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_media_table
```php
$table->id();
$table->morphs('mediable'); // mediable_id + mediable_type + index
$table->string('collection')->default('default');
$table->string('disk')->default('public');
$table->string('path');
$table->string('original_name')->nullable();
$table->string('mime_type')->nullable();
$table->unsignedBigInteger('size')->nullable();
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

---

## Relationships

### SchoolSetting
No relationships. Access via static helper:
```php
SchoolSetting::get('school_name');
SchoolSetting::set('school_name', 'Holy Spirit School');
```

### Staff
```php
// Staff has polymorphic media
public function media(): MorphMany
{
    return $this->morphMany(Media::class, 'mediable');
}

public function photo(): MorphOne
{
    return $this->morphOne(Media::class, 'mediable')
                ->where('collection', 'photo');
}
```

### Media
```php
// Inverse polymorphic
public function mediable(): MorphTo
{
    return $this->morphTo();
}
```

---

## Notes

- `Staff` is intentionally generic. The `role` column distinguishes principal vs. leadership vs. patron.
- Scope staff by role in controllers: `Staff::where('role', 'leadership')->get()`
- `SchoolSetting` should be cached (Laravel `cache()`) — it is read on every page render.
- `Media` replaces all direct `photo_path` / `file_url` string columns — use the polymorphic relation instead of raw strings where upload management is needed.

# Digital Services Page CMS

> No external dependencies. Standalone phase.

---

## Models

### Resource

Curated links/resources shown to students, parents, or staff (Resources tab).

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| description | text | yes | |
| audience | enum | no | `all`, `students`, `parents`, `staff` |
| icon | string | yes | icon name for UI rendering |
| icon_color | string | yes | Tailwind color class e.g. `text-rose-600` |
| url | string | no | external or internal link |
| is_active | boolean | no | default true |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### CalendarEntry

Academic calendar entries shown in the Calendar tab (terms, holidays, exams, events).

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| title | string | no | |
| type | enum | no | `term`, `holiday`, `exam`, `event` |
| date_start | date | no | |
| date_end | date | yes | null = single-day entry |
| description | text | yes | |
| is_active | boolean | no | default true |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### LeavingCertificate

Issued leaving certificates. Each record represents one cert that can be requested and verified.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| student_index_number | string | no | unique per cert |
| student_name | string | no | |
| year_group | string | yes | |
| academic_year | string | yes | e.g. "2024/2025" |
| verification_code | string | no | unique, auto-generated alphanumeric |
| issued_at | date | yes | |
| is_active | boolean | no | default true |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### LcRequest

A request made by a parent/student to obtain their leaving certificate.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| leaving_certificate_id | unsignedBigInteger | yes | FK → leaving_certificates (null until matched) |
| student_index_number | string | no | submitted by requester |
| parent_phone | string | no | submitted by requester |
| access_code | string | yes | 6-digit code sent to parent phone |
| access_code_expires_at | timestamp | yes | |
| stage | enum | no | `pending`, `code_sent`, `accessed`, `failed` |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_resources_table
php artisan make:migration create_calendar_entries_table
php artisan make:migration create_leaving_certificates_table
php artisan make:migration create_lc_requests_table
```

### create_resources_table
```php
$table->id();
$table->string('title');
$table->text('description')->nullable();
$table->enum('audience', ['all', 'students', 'parents', 'staff'])->default('all');
$table->string('icon')->nullable();
$table->string('icon_color')->nullable();
$table->string('url');
$table->boolean('is_active')->default(true);
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_calendar_entries_table
```php
$table->id();
$table->string('title');
$table->enum('type', ['term', 'holiday', 'exam', 'event']);
$table->date('date_start');
$table->date('date_end')->nullable();
$table->text('description')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### create_leaving_certificates_table
```php
$table->id();
$table->string('student_index_number')->unique();
$table->string('student_name');
$table->string('year_group')->nullable();
$table->string('academic_year')->nullable();
$table->string('verification_code')->unique();
$table->date('issued_at')->nullable();
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### create_lc_requests_table
```php
$table->id();
$table->foreignId('leaving_certificate_id')
      ->nullable()
      ->constrained('leaving_certificates')
      ->nullOnDelete();
$table->string('student_index_number');
$table->string('parent_phone');
$table->string('access_code')->nullable();
$table->timestamp('access_code_expires_at')->nullable();
$table->enum('stage', ['pending', 'code_sent', 'accessed', 'failed'])->default('pending');
$table->timestamps();
```

---

## Relationships

### Resource
No relations. Standalone filterable content.

### CalendarEntry
No relations. Standalone dated content.

### LeavingCertificate
```php
public function requests(): HasMany
{
    return $this->hasMany(LcRequest::class);
}
```

### LcRequest
```php
public function certificate(): BelongsTo
{
    return $this->belongsTo(LeavingCertificate::class, 'leaving_certificate_id');
}
```

---

## Notes

- **Verification flow:**
  1. Parent submits index number + phone → creates `LcRequest` with `stage = pending`
  2. System looks up `LeavingCertificate` by `student_index_number` — if found, generate 6-digit `access_code`, SMS it, set `access_code_expires_at = now()->addMinutes(15)`, update `stage = code_sent`
  3. Parent enters code → validate code + expiry → `stage = accessed`, return cert details
- **Certificate Verification tab** — public lookup by `verification_code` only: `LeavingCertificate::where('verification_code', $code)->where('is_active', true)->first()`
- `LeavingCertificate.verification_code` generation (in observer):
  ```php
  static::creating(function ($cert) {
      $cert->verification_code = strtoupper(Str::random(12));
  });
  ```
- `Resource.audience` filter: `Resource::where('audience', $audience)->orWhere('audience', 'all')`

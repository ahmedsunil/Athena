# Admissions Page CMS

> No external dependencies. Standalone phase (most complex form logic).

---

## Models

### AdmissionSetting

Singleton config for the admissions page — overview text, requirements list, key dates.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| overview | text | no | intro paragraph |
| requirements | json | no | array of requirement strings |
| key_dates | json | no | array of `{date, label, note}` objects |
| updated_at | timestamp | yes | |

> Single row. Use `AdmissionSetting::first()`.

**`requirements` JSON schema:**
```json
["Original birth certificate", "2 passport photos", "Report card from previous school"]
```

**`key_dates` JSON schema:**
```json
[
  { "date": "2026-01-15", "label": "Applications Open", "note": null },
  { "date": "2026-03-31", "label": "Deadline", "note": "No extensions" }
]
```

---

### YearGroupSlot

Availability status per year group for new applicants.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| label | string | no | e.g. "Year 7", "Form 1" |
| status | enum | no | `open`, `full`, `waitlist` |
| sort_order | unsignedInteger | no | default 0 |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

### ApplicationSubmission

Records submitted via the multi-step admissions form.

| Column | Type | Nullable | Notes |
|--------|------|----------|-------|
| id | bigIncrements | no | |
| reference_number | string | no | unique, auto-generated e.g. `APP-2026-XXXXX` |
| **Step 1 — Student** | | | |
| student_first_name | string | no | |
| student_last_name | string | no | |
| date_of_birth | date | no | |
| gender | enum | no | `male`, `female`, `other` |
| nationality | string | yes | |
| **Step 2 — Guardian** | | | |
| guardian_first_name | string | no | |
| guardian_last_name | string | no | |
| relationship | string | no | e.g. "Mother", "Father", "Guardian" |
| guardian_phone | string | no | |
| guardian_email | string | no | |
| **Step 3 — School Background** | | | |
| applied_year_group | string | no | e.g. "Year 7" |
| current_school | string | yes | |
| current_year_group | string | yes | |
| reason_for_transfer | text | yes | |
| **Status** | | | |
| stage | enum | no | `submitted`, `reviewing`, `interview`, `accepted`, `rejected` |
| stage_notes | text | yes | internal CMS notes |
| created_at | timestamp | yes | |
| updated_at | timestamp | yes | |

---

## Migrations

```bash
php artisan make:migration create_admission_settings_table
php artisan make:migration create_year_group_slots_table
php artisan make:migration create_application_submissions_table
```

### create_admission_settings_table
```php
$table->id();
$table->text('overview');
$table->json('requirements');
$table->json('key_dates');
$table->timestamps();
```

### create_year_group_slots_table
```php
$table->id();
$table->string('label');
$table->enum('status', ['open', 'full', 'waitlist'])->default('open');
$table->unsignedInteger('sort_order')->default(0);
$table->timestamps();
```

### create_application_submissions_table
```php
$table->id();
$table->string('reference_number')->unique();
// Student
$table->string('student_first_name');
$table->string('student_last_name');
$table->date('date_of_birth');
$table->enum('gender', ['male', 'female', 'other']);
$table->string('nationality')->nullable();
// Guardian
$table->string('guardian_first_name');
$table->string('guardian_last_name');
$table->string('relationship');
$table->string('guardian_phone');
$table->string('guardian_email');
// Background
$table->string('applied_year_group');
$table->string('current_school')->nullable();
$table->string('current_year_group')->nullable();
$table->text('reason_for_transfer')->nullable();
// Status
$table->enum('stage', ['submitted', 'reviewing', 'interview', 'accepted', 'rejected'])
      ->default('submitted');
$table->text('stage_notes')->nullable();
$table->timestamps();
```

---

## Relationships

### AdmissionSetting
No relations. Single-row config.
```php
protected $casts = [
    'requirements' => 'array',
    'key_dates' => 'array',
];
```

### YearGroupSlot
No relations. Independent lookup table.

### ApplicationSubmission
No relations. Self-contained submission record.

---

## Notes

- **Reference number generation** — generate in `creating` model observer:
  ```php
  static::creating(function ($app) {
      $app->reference_number = 'APP-' . now()->year . '-' . strtoupper(Str::random(5));
  });
  ```
- **Application Tracker** on the frontend: `ApplicationSubmission::where('reference_number', $ref)->first()` — return `stage` and computed stage list.
- **Stage progression** should send email notifications — wire up `Mail` in an Observer on `stage` change.
- `AdmissionSetting.key_dates` stores ISO dates — cast strings to `Carbon` in the resource/presenter.
- `YearGroupSlot` is editable in CMS — admin updates `status` per year group as slots fill up.

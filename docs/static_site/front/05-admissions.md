# Admissions Page Frontend

Based on `cms/04-admissions.md`.

Route:

```text
GET /admissions
```

Livewire page:

```text
app/Livewire/Pages/Admissions.php
resources/views/livewire/pages/admissions.blade.php
```

## Sections

1. admissions overview from `AdmissionSetting`
2. requirements list
3. key dates
4. year group availability from `YearGroupSlot`
5. multi-step application form
6. application tracker by reference number

## Data Queries

```php
$settings = AdmissionSetting::query()->first();

$slots = YearGroupSlot::query()
    ->orderBy('sort_order')
    ->get();
```

## Application Form

Livewire component:

```text
app/Livewire/Components/ApplicationForm.php
```

Steps:

1. student details
2. guardian details
3. school background
4. review and submit

Writes to `application_submissions`.

Required fields:

- student first name
- student last name
- date of birth
- gender
- guardian first name
- guardian last name
- relationship
- guardian phone
- guardian email
- applied year group

Submission behavior:

- generate `reference_number` in the model or service
- set `stage = submitted`
- show the reference number after submit
- send a confirmation email if mail is configured
- never expose internal `stage_notes`

## Application Tracker

Livewire component:

```text
app/Livewire/Components/ApplicationTracker.php
```

Input:

- `reference_number`

Output:

- current stage
- stage labels: submitted, reviewing, interview, accepted, rejected
- generic not-found message if the reference number is invalid

Do not expose guardian email, phone, or internal notes from tracker results.

## Empty States

- If `AdmissionSetting` is missing, render a minimal message and contact details from `SchoolSetting`.
- If no slots exist, hide the availability table.

## SEO

Title:

```text
Admissions | Hulhudhuffaaru School
```

Description should mention requirements, key dates, and online application availability.


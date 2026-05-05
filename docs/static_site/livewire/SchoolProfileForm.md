# SchoolProfile / Form

**Class:** `App\Livewire\Cms\SchoolProfile\Form`  
**View:** `livewire.cms.school-profile.form`  
**DB table:** `school_profiles` (single row, `id = 1`)

## Purpose

Create or update the school's identity data. Single-record pattern — always `updateOrCreate(['id' => 1])`.

---

## State properties

| Property | Type | Description |
|----------|------|-------------|
| `profileId` | `?int` | DB id, null if no record yet |
| `name` | `string` | School name |
| `founded_year` | `mixed` | 4-digit year |
| `motto` | `?string` | School motto |
| `tagline` | `?string` | Short tagline |
| `description` | `?string` | Long description |
| `logo_path` | `?string` | Stored path of current logo |
| `mission_statement` | `?string` | Mission text |
| `vision_statement` | `?string` | Vision text |
| `contact_address` | `string` | Physical address |
| `contact_phone` | `string` | Phone number |
| `contact_email` | `string` | Contact email |
| `logo` | `mixed` | Livewire temp upload (`WithFileUploads`) |

---

## DB schema (`school_profiles`)

| Column | Type | Notes |
|--------|------|-------|
| `id` | bigint PK | |
| `name` | string | required |
| `founded_year` | year | required, 4 digits |
| `motto` | string | nullable |
| `tagline` | string | nullable |
| `description` | longText | nullable |
| `logo_path` | string | stored in `public/school/` |
| `hero_image_path` | string | not managed by this form |
| `mission_statement` | text | nullable |
| `vision_statement` | text | nullable |
| `contact_address` | text | required |
| `contact_phone` | text | required |
| `contact_email` | text | required |

---

## Key methods

### `mount()`
Loads first `SchoolProfile` row into properties. No-op if no record exists yet.

### `save()`
1. Validates all fields
2. If `$logo` uploaded → stores to `public` disk at `school/{filename}`, sets `logo_path`
3. `SchoolProfile::updateOrCreate(['id' => 1], $data)`
4. Clears `$logo`, updates `$logo_path`
5. Dispatches `toast` event

### Logo upload
Uses `Livewire\WithFileUploads`. `logo` field required only if no existing `logo_path`.  
Max 2 MB. Stored via `$this->logo->store('school', 'public')`.

---

## Reading this data (other project, same DB)

```php
$profile = SchoolProfile::first();

$profile->name;
$profile->founded_year;
$profile->motto;
$profile->tagline;
$profile->description;
$profile->logo_path;        // use Storage::url($profile->logo_path)
$profile->mission_statement;
$profile->vision_statement;
$profile->contact_address;
$profile->contact_phone;
$profile->contact_email;
```

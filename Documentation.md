# CMS API Documentation

## Overview

This document covers the API setup for serving public data from the CMS (LiveWire) to a separate public website (jQuery/Fetch).

---

## 1. API Components Setup

### 1.1 API Resource

**File:** `app/Http/Resources/SchoolProfileResource.php`

Transforms the SchoolProfile model into JSON response.

```php
public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'founded_year' => $this->founded_year,
        'motto' => $this->motto,
        'tagline' => $this->tagline,
        'description' => $this->description,
        'logo_path' => $this->logo_path,
        'hero_image_path' => $this->hero_image_path,
        'mission_statement' => $this->mission_statement,
        'vision_statement' => $this->vision_statement,
        'contact' => [
            'address' => $this->contact_address,
            'phone' => $this->contact_phone,
            'email' => $this->contact_email,
        ],
    ];
}
```

### 1.2 API Controller

**File:** `app/Http/Controllers/API/SchoolProfileController.php`

```php
public function show()
{
    $profile = SchoolProfile::first();

    if (!$profile) {
        return response()->json(['message' => 'School profile not found'], 404);
    }

    return new SchoolProfileResource($profile);
}
```

### 1.3 API Routes

**File:** `routes/api.php`

```php
use App\Http\Controllers\API\SchoolProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('public.api')->group(function () {
    Route::get('/school-profile', [SchoolProfileController::class, 'show']);
});
```

---

## 2. Public API Key Authentication

### 2.1 Middleware

**File:** `app/Http/Middleware/CheckPublicApiKey.php`

Validates the `X-API-KEY` header against the configured key.

```php
public function handle(Request $request, Closure $next): Response
{
    $apiKey = $request->header('X-API-KEY');

    if (!$apiKey || $apiKey !== config('app.public_api_key')) {
        return response()->json([
            'message' => 'Unauthenticated',
            'error' => 'Invalid or missing API key'
        ], 401);
    }

    return $next($request);
}
```

### 2.2 Middleware Registration

**File:** `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'public.api' => \App\Http\Middleware\CheckPublicApiKey::class,
    ]);
})
```

### 2.3 Environment Variable

**File:** `.env`

```
PUBLIC_API_KEY=sk_school_public_2024_secure_key
```

---

## 3. Seeder

**File:** `database/seeders/SchoolProfileSeeder.php`

```php
public function run(): void
{
    SchoolProfile::firstOrCreate(
        ['id' => 1],
        [
            'name' => 'Bright Future Academy',
            'founded_year' => 1995,
            'motto' => 'Educating Tomorrow\'s Leaders',
            'tagline' => 'Excellence in Education',
            'description' => 'A premier educational institution...',
            'logo_path' => 'school/logo.png',
            'hero_image_path' => 'school/hero.jpg',
            'mission_statement' => 'To provide quality education...',
            'vision_statement' => 'To be a leading educational institution...',
            'contact_address' => '123 Education Street, City, Country',
            'contact_phone' => '+960 123 4567',
            'contact_email' => 'info@brightfuture.edu',
        ]
    );
}
```

Registered in `database/seeders/DatabaseSeeder.php`:
```php
$this->call([
    RolesAndPermissionsSeeder::class,
    SchoolProfileSeeder::class,
]);
```

---

## 4. API Usage

### 4.1 Endpoint

```
GET /api/school-profile
```

### 4.2 Required Header

```
X-API-KEY: sk_school_public_2024_secure_key
```

### 4.3 Sample Response

```json
{
    "data": {
        "id": 1,
        "name": "Bright Future Academy",
        "founded_year": 1995,
        "motto": "Educating Tomorrow's Leaders",
        "tagline": "Excellence in Education",
        "description": "A premier educational institution...",
        "logo_path": "school/logo.png",
        "hero_image_path": "school/hero.jpg",
        "mission_statement": "...",
        "vision_statement": "...",
        "contact": {
            "address": "123 Education Street, City, Country",
            "phone": "+960 123 4567",
            "email": "info@brightfuture.edu"
        }
    }
}
```

### 4.4 jQuery/Fetch Example

```javascript
fetch('http://cms.domain.com/api/school-profile', {
    headers: {
        'X-API-KEY': 'sk_school_public_2024_secure_key'
    }
})
.then(response => response.json())
.then(data => {
    document.getElementById('school-name').textContent = data.data.name;
    document.getElementById('school-motto').textContent = data.data.motto;
});
```

---

## 5. Form Components

**Location:** `resources/views/components/admin/forms/`

Components available:
- `text-input.blade.php` - Text input with optional prefix
- `textarea.blade.php` - Multi-line text input
- `select-input.blade.php` - Dropdown select
- `checkbox.blade.php` - Checkbox
- `toggle-switch.blade.php` - Toggle switch
- `file-upload.blade.php` - File upload
- `form-field.blade.php` - Wrapper with label + error handling
- `form-grid.blade.php` - Grid layout (1-3 columns)

### Usage Example

```blade
<x-admin.forms.form-grid cols="2">
    <x-admin.forms.form-field label="Name" field="name" required>
        <x-admin.forms.text-input wire:model="name" />
    </x-admin.forms.form-field>

    <x-admin.forms.form-field label="Email" field="email" required>
        <x-admin.forms.text-input wire:model="email" type="email" />
    </x-admin.forms.form-field>
</x-admin.forms.form-grid>
```

---

## 6. Security Features

- **API Key Gatekeeper** - Prevents unauthorized access to public API
- **Rate Limiting Ready** - Can add `throttle:60,1` middleware if needed
- **404 for Missing Data** - Graceful handling when no profile exists
- **CORS** - Handled by Laravel for same-domain requests

---

## 7. Commands

```bash
# Seed school profile data
php artisan db:seed --class=SchoolProfileSeeder

# Clear routes cache
php artisan route:clear

# List API routes
php artisan route:list --path=api
```
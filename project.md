# Athena Project Context & Wiring Guide

This guide provides a comprehensive overview of the Athena application architecture, directory structure, and core logic patterns. It is designed to enable AI agents to generate consistent, idiomatic code for this specific workspace.

---

## 1. Application Stack
- **Framework:** Laravel 11 (PHP 8.2+)
- **Frontend Logic:** Livewire 3
- **UI System:** Athena Admin System (Custom Tailwind-based Design System)
- **Auth:** Laravel Fortify + Google OAuth
- **Authorization:** Spatie Laravel Permission
- **Auditing:** Spatie Activity Log
- **Database:** PostgreSQL (assumed) / MySQL

---

## 2. Directory Map (Excluding Vendor/Storage)

```text
/
├── api_jsons/          # Source of truth for CMS data structures (JSON)
├── app/
│   ├── Helpers/        # Global helpers (e.g., BreadcrumbHelper)
│   ├── Http/
│   │   ├── Controllers/# Google Auth, Base Controller
│   │   └── Middleware/ # Public API Key validation
│   ├── Livewire/       # CORE LOGIC: All UI components (Dashboard, CMS, Auth)
│   ├── Models/         # User, Setting, HomePage, Event, SchoolProfile
│   └── Providers/      # AppServiceProvider, FortifyServiceProvider
├── bootstrap/          # App initialization and provider registration
├── config/             # App, Auth, Fortify, Permission, ActivityLog
├── database/
│   ├── migrations/     # Schema definitions
│   └── seeders/        # Roles, SchoolProfile, HomePage seeders
├── resources/
│   ├── admin-system/   # DESIGN SYSTEM: Tokens, Skeletons, Components
│   ├── css/            # app.css
│   ├── js/             # app.js
│   └── views/
│       ├── components/ # Blade components (x-admin.*)
│       ├── layouts/    # app.blade.php (Main), guest.blade.php
│       └── livewire/   # Livewire Blade templates
├── routes/
│   ├── api.php         # Public data endpoints
│   └── web.php         # Livewire routes (protected by auth)
└── System Design/      # Reference/Template project (DO NOT MODIFY)
```

---

## 3. Athena UI System (Wiring)

The UI is driven by **Design Tokens**. Do not invent new Tailwind classes; use the tokens as reference.

### 3.1 Token Files (`resources/admin-system/tokens/`)
- **`components.php`**: The primary reference. Maps component names (e.g., `button.primary`) to Tailwind strings.
- **`colors.php`**: Defines the palette (`teal-600` is primary).
- **`typography.php`**: Font sizes and semantic text styles (e.g., `styles.page_title`).

### 3.2 Usage Pattern
**Direct String Usage (Phase 1-2):**
Paste classes directly from tokens into Blade files.

**Blade Components (Phase 3+):**
Use `<x-admin.forms.text-input>` or `<x-admin.stat-card>`.

---

## 4. Core Wiring & Conventions

### 4.1 Livewire Component Structure
Livewire components are the "controllers" of the UI.
- **Location:** `app/Livewire/`
- **Namespace:** `App\Livewire\...`
- **View:** `resources/views/livewire/...` (kebab-case)

Example Wiring:
```php
// app/Livewire/Users/UserForm.php
class UserForm extends Component {
    public User $user;
    public $roles = [];
    
    public function save() {
        $this->validate();
        $this->user->save();
        session()->flash('toast', 'User saved successfully');
    }
    
    public function render() {
        return view('livewire.users.user-form')->layout('layouts.app');
    }
}
```

### 4.2 Routing
- **Web:** All admin routes are in `routes/web.php` and wrapped in `auth`, `verified` middleware.
- **API:** Public endpoints in `routes/api.php` use `public.api` middleware requiring an `X-API-KEY` header.

### 4.3 Models & Data
- **`Setting`**: Key-value store for system-wide configuration.
- **`HomePage`**: Stores a `payload` (JSON) defined by the structure in `api_jsons/home.json`.
- **`SchoolProfile`**: One-row table for school branding/contact info.

---

## 5. Core File Contents (Summaries)

### `routes/web.php`
Registers Livewire components directly as routes:
```php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', UsersList::class)->name('users.index');
    // ...
});
```

### `app/Http/Middleware/CheckPublicApiKey.php`
Protects the API. Expects `X-API-KEY` header matching `config('app.public_api_key')`.

### `resources/views/layouts/app.blade.php`
Main layout. Loads `Inter` and `IBM Plex Mono` fonts. Contains the sidebar include and the Toast notification system (Alpine.js).

---

## 6. AI Instructions for Code Generation

1.  **UI Consistency:** Always check `resources/admin-system/tokens/components.php` before adding HTML to a Blade file. Match the classes exactly.
2.  **Naming:**
    - Livewire Classes: `PascalCase` (e.g., `EventList`)
    - Livewire Views: `kebab-case` (e.g., `event-list.blade.php`)
    - Routes: `kebab-case.dot` (e.g., `events.index`)
3.  **Interaction:** Use Alpine.js for lightweight UI state (modals, tabs) and Livewire for server-side state/actions.
4.  **Feedback:** Use `session()->flash('toast', 'Message')` for success notifications. The layout handles the display.
5.  **Form Validation:** Always include `@error('field_name') <p class="...">{{ $message }}</p> @enderror` using the error style from `tokens/typography.php`.
6.  **API Data:** When building CMS features, ensure the output matches the JSON schemas defined in `api_jsons/`.

---

## 7. Useful Commands
```bash
# Generate a new Livewire component
php artisan make:livewire Cms.SectionName.Component

# Run migrations
php artisan migrate

# Seed data
php artisan db:seed --class=RolesAndPermissionsSeeder
```

This guide ensures that any generated code adheres to the Athena "Admin System" architecture and maintains system integrity.

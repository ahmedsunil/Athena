# Laravel Livewire Starter Kit

A production-ready admin panel starter kit built on **Laravel 13**, **Livewire 4**, **Fortify**, **Alpine.js**, and **Tailwind CSS**.

## Stack

| Layer | Package | Version |
|---|---|---|
| Framework | laravel/framework | ^13.3 |
| Reactive UI | livewire/livewire | ^4.2 |
| Auth backend | laravel/fortify | ^1.x |
| Browser detect | jenssegers/agent | ^2.6 |
| CSS | Tailwind CSS | ^4.x |
| JS | Alpine.js | ^3.x |

---

## Installation

### 1. Clone and install dependencies

```bash
composer install
npm install && npm run build
```

### 2. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

SESSION_DRIVER=database   # required for browser sessions feature
```

### 3. Run migrations

```bash
php artisan migrate
```

### 4. Create the first admin user

```bash
php artisan tinker
```

```php
App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@example.com',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

### 5. Serve

```bash
php artisan serve
```

---

## Features

### Authentication (via Laravel Fortify)
- Login with rate limiting (5 attempts / minute)
- Registration
- Password reset via email
- Email verification
- Password confirmation for sensitive actions
- Two-factor authentication (TOTP + recovery codes)
- Inactive user blocking (users with `is_active = false` cannot log in)

### Profile Settings
- Update name & email
- Change password
- Enable / confirm / disable 2FA
- View and revoke browser sessions
- Delete account

### Dashboard
- User stats (total, active, admins, new this month)
- Recent users table (desktop) / card list (mobile)

### User Management
- Searchable, filterable, sortable paginated list
- Bulk delete
- Toggle active/inactive without full page reload
- Create / Edit / Delete individual users
- Role assignment (user / admin)

---

## Project Structure

```
app/
  Livewire/
    Auth/           — Login, Register, ForgotPassword, ResetPassword,
                      VerifyEmail, ConfirmPassword, TwoFactorChallenge
    Profile/        — UpdateProfileInformation, UpdatePassword,
                      TwoFactorAuthentication, BrowserSessions,
                      DeleteAccount, ProfileSettings
    Users/          — UsersList, UserForm
    Dashboard.php

resources/
  views/
    layouts/
      app.blade.php          — Main shell (sidebar + topbar + toast)
      guest.blade.php        — Centered card for auth pages
      partials/sidebar.blade.php
    livewire/
      auth/                  — Blade views for each auth component
      profile/               — Blade views for each profile section
      users/                 — users-list, user-form
      dashboard.blade.php
    auth/                    — Thin wrappers: <x-guest-layout><livewire:... /></x-guest-layout>

  admin-system/              — Design system (tokens, components, skeletons, docs)

config/
  fortify.php                — All features enabled, views registered

routes/
  web.php                    — Protected routes grouped under auth + verified
```

---

## Design System

All design tokens live in `resources/admin-system/tokens/`. They are plain PHP files that return arrays of Tailwind class strings — no build step required to read them.

| File | Contains |
|---|---|
| `colors.php` | Brand, neutral, status, danger palettes |
| `spacing.php` | Page, card, table, form, sidebar gaps |
| `typography.php` | Font sizes, weights, preset style strings |
| `radius.php` | Border radius scale with usage contexts |
| `shadows.php` | Card, dropdown, modal shadow levels |
| `components.php` | Full class strings for every UI variant |

See `resources/admin-system/docs/GUIDE.md` for usage examples.

---

## Customisation

### Change the brand color

Replace `teal` with any Tailwind color (e.g. `indigo`, `violet`, `blue`) across:
- `resources/views/layouts/` — sidebar active state, focus rings
- `resources/views/livewire/` — buttons, focus rings, success notices
- `resources/admin-system/tokens/colors.php` — update `primary.*` values

### Add navigation items

Edit `resources/views/layouts/partials/sidebar.blade.php` — the `$navGroups` array controls groups and links:

```php
$navGroups = [
    'Overview'   => [['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => '...']],
    'Management' => [['label' => 'Users', 'route' => 'users.index', 'icon' => '...']],
];
```

### Add a new module

1. Create the Livewire class: `app/Livewire/YourModule/YourList.php`
2. Create the Blade view: `resources/views/livewire/your-module/your-list.blade.php`
3. Add the route to `routes/web.php`
4. Add the nav link to `sidebar.blade.php`

### Disable a Fortify feature

In `config/fortify.php`, remove the feature from the `features` array:

```php
Features::registration(),   // remove this line to disable registration
```

---

## Notes

- `bootstrap/providers.php` already registers `AppServiceProvider` and `FortifyServiceProvider` — no manual step needed.
- `jenssegers/agent` is used only by `BrowserSessions`. If you remove that feature, you can drop the package.
- The `SESSION_DRIVER=database` setting is required for browser sessions to work. Run `php artisan session:table` if your project doesn't already have the sessions migration.

---

## License

MIT
# livewire4-skeleton

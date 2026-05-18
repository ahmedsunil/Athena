# Google Login Implementation Guide

How Google OAuth is implemented in this project. Follow these steps to replicate it in another Laravel app.

---

## Overview

- Package: **Laravel Socialite** (`laravel/socialite`)
- Credentials stored in **database** (not `.env`), encrypted at rest
- Login flow: user must **already exist** in the system — Google is an authentication method only, not registration
- Session-based auth using Laravel's default `web` guard

---

## 1. Install Socialite

```bash
composer require laravel/socialite
```

---

## 2. Database

### 2a. Add `google_id` to users table

```php
// database/migrations/xxxx_add_google_id_to_users_table.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('google_id')->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('google_id');
    });
}
```

### 2b. Settings table (if you don't have one)

Stores Google credentials in the DB so they can be managed via admin UI without touching `.env`.

```php
// database/migrations/xxxx_create_settings_table.php
Schema::create('settings', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->text('value')->nullable();
    $table->timestamps();
});
```

Seed the default Google settings:

```php
DB::table('settings')->insert([
    ['key' => 'google_login_enabled', 'value' => '0'],
    ['key' => 'google_client_id',     'value' => ''],
    ['key' => 'google_client_secret', 'value' => ''],  // stored encrypted
    ['key' => 'google_redirect_uri',  'value' => '/auth/google/callback'],
]);
```

---

## 3. Setting Model

```php
// app/Models/Setting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['key', 'value'];

    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::rememberForever("setting:{$key}", function () use ($key, $default) {
            $setting = static::find($key);
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting:{$key}");
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value);
        }
    }
}
```

Cache is forever — `set()` busts it on write. No cache driver requirement beyond the default (file cache works fine).

---

## 4. User Model

Add `google_id` to `$fillable`:

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',   // ← add this
    'is_active',
];
```

---

## 5. Routes

```php
// routes/web.php
use App\Http\Controllers\GoogleAuthController;

Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
```

**Important:** Do NOT put the callback route behind `auth` middleware — the user is not logged in yet when Google calls it.

---

## 6. Controller

```php
// app/Http/Controllers/GoogleAuthController.php
namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $this->configureSocialite();

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $this->configureSocialite();

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception) {
            return redirect()->route('login')->with('error', 'Google login failed. Please try again.');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            return redirect()->route('login')->with('error', 'Your Google account is not registered in this system.');
        }

        if (! $user->is_active) {
            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        $user->update(['google_id' => $googleUser->getId()]);

        Auth::login($user, true);
        Session::regenerate();

        return redirect()->intended(route('dashboard'));
    }

    private function configureSocialite(): void
    {
        $encryptedSecret = Setting::get('google_client_secret', '');
        $secret = '';

        if ($encryptedSecret) {
            try {
                $secret = Crypt::decryptString($encryptedSecret);
            } catch (Exception) {
                $secret = '';
            }
        }

        config([
            'services.google.client_id'     => Setting::get('google_client_id'),
            'services.google.client_secret' => $secret,
            'services.google.redirect'      => Setting::get('google_redirect_uri', url('/auth/google/callback')),
        ]);
    }
}
```

### Key decisions

| Decision | Why |
|----------|-----|
| `configureSocialite()` called at runtime | Credentials come from DB, not static config |
| `Crypt::decryptString()` in try/catch | Handles corrupt/missing encrypted values gracefully |
| `Auth::login($user, true)` | Second arg = remember me (persistent session) |
| `Session::regenerate()` | Prevents session fixation attacks |
| `redirect()->intended(...)` | Respects any pre-login redirect the user was attempting |
| No auto-registration | User must exist — Google is auth only, not signup |

---

## 7. Admin Settings UI (optional but recommended)

A Livewire component or plain controller form that lets admins configure credentials without touching the server.

### Component properties

```php
public bool   $googleLoginEnabled = false;
public string $googleClientId     = '';
public string $googleClientSecret = '';
public string $googleRedirectUri  = '';
```

### Load on mount

```php
public function mount(): void
{
    $this->googleLoginEnabled = (bool) Setting::get('google_login_enabled', false);
    $this->googleClientId     = (string) Setting::get('google_client_id', '');
    $this->googleRedirectUri  = (string) Setting::get('google_redirect_uri', url('/auth/google/callback'));

    $encrypted = Setting::get('google_client_secret', '');
    try {
        $this->googleClientSecret = $encrypted ? Crypt::decryptString($encrypted) : '';
    } catch (Exception) {
        $this->googleClientSecret = '';
    }
}
```

### Save

```php
public function saveGoogle(): void
{
    $this->validate([
        'googleClientId'     => $this->googleLoginEnabled ? ['required', 'string'] : ['nullable', 'string'],
        'googleClientSecret' => $this->googleLoginEnabled ? ['required', 'string'] : ['nullable', 'string'],
        'googleRedirectUri'  => ['required', 'url'],
    ]);

    Setting::setMany([
        'google_login_enabled' => $this->googleLoginEnabled ? '1' : '0',
        'google_client_id'     => $this->googleClientId,
        'google_client_secret' => $this->googleClientSecret ? Crypt::encryptString($this->googleClientSecret) : '',
        'google_redirect_uri'  => $this->googleRedirectUri,
    ]);
}
```

**Client secret is always stored encrypted.** Never store it plaintext.

---

## 8. Login Page Button

Only render the button when Google login is enabled:

```blade
@if(\App\Models\Setting::get('google_login_enabled') === '1')
    <a href="{{ route('auth.google') }}"
       class="flex w-full items-center justify-center gap-2.5 rounded-lg border border-zinc-200 bg-white px-4 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-5 w-5">
            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
            <path fill="none" d="M0 0h48v48H0z"/>
        </svg>
        Sign in with Google
    </a>
@endif
```

---

## 9. Getting Google OAuth Credentials

1. Go to [console.cloud.google.com](https://console.cloud.google.com) → **APIs & Services** → **Credentials**
2. Click **Create Credentials** → **OAuth 2.0 Client ID**
3. Application type: **Web application**
4. Under **Authorised redirect URIs**, add: `https://yourdomain.com/auth/google/callback`
5. Copy the **Client ID** and **Client Secret** into your admin settings panel

---

## 10. Full Auth Flow Diagram

```
User clicks "Sign in with Google"
        ↓
GET /auth/google
        ↓
GoogleAuthController::redirect()
  - loads credentials from DB
  - configures Socialite at runtime
  - Socialite::driver('google')->redirect()
        ↓
Google OAuth consent screen
        ↓
GET /auth/google/callback?code=...
        ↓
GoogleAuthController::callback()
  - loads credentials from DB
  - Socialite::driver('google')->user()  ← exchanges code for user data
  - User::where('email', ...)->first()   ← must exist in DB
  - check is_active
  - $user->update(['google_id' => ...])
  - Auth::login($user, true)
  - Session::regenerate()
  - redirect()->intended(route('dashboard'))
```

---

## Checklist for a New Project

- [ ] `composer require laravel/socialite`
- [ ] Migration: `google_id` nullable string on `users`
- [ ] Migration: `settings` table (key/value)
- [ ] Seed default Google settings rows
- [ ] `Setting` model with cached `get()`/`set()`
- [ ] `google_id` in `User::$fillable`
- [ ] Two routes (`/auth/google`, `/auth/google/callback`) — no auth middleware on callback
- [ ] `GoogleAuthController` with `redirect()`, `callback()`, `configureSocialite()`
- [ ] Admin UI to set credentials (client secret encrypted with `Crypt::encryptString`)
- [ ] Login page button gated on `google_login_enabled === '1'`
- [ ] Redirect URI added in Google Cloud Console
- [ ] `APP_KEY` must be set — Laravel uses it for `Crypt`

---

## Notes

- **No `.env` Google vars needed.** All config is DB-driven and set at runtime via `config([...])`.
- **`APP_KEY` is critical.** `Crypt::encryptString` uses it. If it changes, stored secrets become unreadable — re-enter them in admin settings.
- **Stateless OAuth not used.** Standard stateful flow with session. Do not add `->stateless()` unless you have a reason.
- **User must pre-exist.** This is by design — no auto-registration. If your project needs auto-registration on Google login, add a `User::create(...)` branch in the `if (! $user)` block.

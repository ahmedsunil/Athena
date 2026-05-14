# Security Fixes Patch

Date: 2026-05-14

## Applied fixes

### 1. Restrict admin and CMS routes to admins

Files changed:

- `bootstrap/app.php`
- `routes/web.php`

Changes:

- Registered Spatie middleware aliases:
  - `role`
  - `permission`
  - `role_or_permission`
- Kept `/profile` available to authenticated verified users.
- Moved these sensitive areas behind `auth`, `verified`, and `role:admin`:
  - `/dashboard`
  - `/users`
  - `/roles`
  - `/app/activity`
  - `/app/settings`
  - all `/cms/*` routes

Reason:

Previously, any verified authenticated user could reach CMS, users, roles, app settings, and audit screens.

### 2. Disable public self-registration

File changed:

- `config/fortify.php`

Changes:

- Removed `Features::registration()` from Fortify features.

Reason:

Registration should not be open for a school CMS/admin system unless there is a separate approval flow.

### 3. Prevent Google login from creating new users

File changed:

- `app/Http/Controllers/GoogleAuthController.php`

Changes:

- Removed automatic user creation from Google OAuth callback.
- Google login now only works for existing active users.
- Inactive users are rejected before login.

Reason:

Previously, any Google account could be auto-created as an active user, which combined badly with weak route authorization.

### 4. Tighten announcement attachment validation

File changed:

- `app/Livewire/Cms/Announcements/AnnouncementsIndex.php`

Changes:

- URL attachments now only allow `http` and `https`.
- Uploaded files are restricted to:
  - `pdf`
  - `doc`
  - `docx`
  - `xls`
  - `xlsx`
  - `jpg`
  - `jpeg`
  - `png`
- Upload size remains capped at 10 MB.

Reason:

Previously, arbitrary files could be uploaded to public storage.

### 5. Tighten digital services document upload validation

File changed:

- `app/Livewire/Cms/DigitalServices/DocumentsIndex.php`

Changes:

- Uploaded documents are restricted to:
  - `pdf`
  - `doc`
  - `docx`
  - `xls`
  - `xlsx`
- Upload size remains capped at 10 MB.

Reason:

The CMS form already labels these as document downloads, so validation should enforce that contract.

### 6. Rate-limit the public contact endpoint

Files changed:

- `app/Providers/AppServiceProvider.php`
- `routes/api.php`

Changes:

- Added a `contact` rate limiter: 5 requests per minute per IP.
- Applied `throttle:contact` to `POST /api/contact`.

Reason:

The contact endpoint sends email and should not be callable without rate limiting.

## Pending fixes

### 1. Announcement status separation

Recommended change:

- Add a `status` field to announcements:
  - `active`
  - `closed`
  - `draft`
- Keep `draft` announcements hidden from public list and detail pages.
- Migrate existing inactive announcements to `closed` so the current Closed tab behavior is preserved.

Reason:

`is_active=false` currently means both "closed" and "unpublished/draft". That makes it difficult to hide drafts while still showing closed public notices.

### 2. Dependency update

Recommended command:

```bash
composer update phpseclib/phpseclib
```

Reason:

`composer audit` reported high severity `CVE-2026-44167` / `GHSA-3qpq-r242-jqj7` for `phpseclib/phpseclib` `3.0.51`.

### 3. Production environment hardening

Recommended production values:

```dotenv
APP_ENV=production
APP_DEBUG=false
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
```

Also rotate any exposed API keys if the local `.env` was committed, copied, shared, or used outside the server.

Reason:

The local `.env` contains real-looking secrets and development-safe settings that should not be used in production.

## Suggested tests

- Non-admin authenticated users receive 403 for `/dashboard`, `/users`, `/roles`, `/app/settings`, and `/cms/*`.
- Admin users can still access CMS routes.
- Google login rejects unknown Google accounts.
- Announcement upload rejects `.html`, `.svg`, `.php`, and other non-allowed files.
- Digital services document upload rejects non-document files.
- `/api/contact` returns 429 after exceeding the rate limit.

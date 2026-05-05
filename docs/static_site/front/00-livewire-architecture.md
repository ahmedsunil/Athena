# Livewire 4 Frontend Architecture

The public website should be a separate Laravel + Livewire 4 app that connects to the same database as the CMS.

## Core Rules

- Do not recreate CMS admin screens in this project.
- Do not create duplicate migrations unless the CMS project has not yet shipped them.
- Keep frontend writes limited to public submission flows.
- Keep all page rendering server-side through Livewire and Blade.
- Use public DTOs, presenters, or view models so Blade does not depend on raw CMS storage details.
- Use `wire:navigate` for internal navigation.
- Use Blade components for repeated static UI and Livewire components only where state is needed.

## Suggested Structure

```text
app/
  Livewire/
    Pages/
      Home.php
      About.php
      Academics.php
      Admissions.php
      DigitalServices.php
      Downloads.php
      Gallery.php
      Search.php
      Events/
        Index.php
        Show.php
    Components/
      ContactForm.php
      ApplicationForm.php
      ApplicationTracker.php
      LeavingCertificateRequest.php
      LeavingCertificateVerify.php
  Models/
  Services/
    Frontend/
  ViewModels/
resources/
  views/
    layouts/
      app.blade.php
    livewire/
    components/
routes/
  web.php
tests/
```

## Route Map

Use routed Livewire page components.

```php
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');
Route::livewire('/about', 'pages::about')->name('about');
Route::livewire('/academics', 'pages::academics')->name('academics');
Route::livewire('/admissions', 'pages::admissions')->name('admissions');
Route::livewire('/digital-services', 'pages::digital-services')->name('digital-services');
Route::livewire('/downloads', 'pages::downloads')->name('downloads');
Route::livewire('/gallery', 'pages::gallery')->name('gallery');
Route::livewire('/student-life', 'pages::student-life')->name('student-life');
Route::livewire('/events', 'pages::events.index')->name('events.index');
Route::livewire('/events/{event}', 'pages::events.show')->name('events.show');
Route::livewire('/search', 'pages::search')->name('search');
```

## Layout

Create the default Livewire layout with:

```bash
php artisan livewire:layout
```

The default layout should live at:

```text
resources/views/layouts/app.blade.php
```

The shell includes:

- school logo and name
- primary navigation
- mobile navigation
- search trigger
- page slot
- footer
- persistent search overlay if implemented globally

## Navigation

Use `wire:navigate` on internal links.

```blade
<a href="{{ route('about') }}" wire:navigate class="data-current:text-emerald-700">
    About
</a>
```

Use `data-current` styling for active links. This matters if the nav is wrapped in `@persist`, because request-time active-link checks will not update after Livewire navigation.

## Data Layer

Use a service per public page or feature:

- `HomePageData`
- `AboutPageData`
- `AcademicsPageData`
- `AdmissionPageData`
- `DigitalServicesData`
- `EventDirectory`
- `SearchIndex`

Each service should return arrays or view models shaped for the frontend. Avoid passing raw Eloquent models into deeply nested views unless the view is intentionally model-aware.

## Styling

- Tailwind CSS v4 only.
- Use built-in Tailwind colors and utilities.
- Do not create `tailwind.config.js`.
- Keep the public website visually school-focused and readable.
- Use responsive layouts for mobile, tablet, and desktop.

## Shared Database

The frontend `.env` should point at the CMS database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=hs_cms
DB_USERNAME=...
DB_PASSWORD=...
```

If the frontend and CMS run on different domains, configure storage URLs and CORS intentionally. Images and downloadable files should be served from public storage URLs or signed routes, not private filesystem paths.


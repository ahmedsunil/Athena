# Frontend Website Plan

This directory describes the separate public website project for Hulhudhuffaaru School.

The frontend is a Laravel + Livewire 4 application that reads from the same database used by the CMS. It is not the CMS and should not include admin screens, Filament resources, or content editing workflows.

## Project Boundary

- `cms/` defines the database-backed content model and CMS behavior.
- `front/` defines the public website behavior, Livewire pages, route map, data loading, forms, and public user flows.
- Both projects may contain Eloquent models for the same tables, but the CMS remains the source of truth for migrations and content management.
- The frontend may write to public submission tables such as `contact_submissions`, `application_submissions`, and `lc_requests`.

## Recommended Stack

- Laravel application dedicated to the public website.
- Livewire 4 for routed pages and interactive public flows.
- Blade components for repeated presentational UI.
- Tailwind CSS v4 with built-in utility classes.
- Shared database connection to the CMS database.
- Shared public storage or signed media URLs for uploaded images and files.

## Documents

- `00-livewire-architecture.md` - application structure, routing, layouts, and Livewire 4 conventions.
- `01-shared-data.md` - shared database reads, settings, staff, media, and frontend model rules.
- `02-home.md` - home page sections, queries, and contact form.
- `03-about.md` - about page content, history, mission, leadership, and achievements.
- `04-academics.md` - academic levels and course listing page.
- `05-admissions.md` - admissions content, application form, and tracker.
- `06-digital-services.md` - resources, calendar, and leaving certificate flows.
- `07-events.md` - events index and event detail pages.
- `08-gallery-downloads.md` - gallery albums and document downloads.
- `09-student-life.md` - clubs, houses, prefects, and scout groups.
- `10-search-seo-tests.md` - search behavior, SEO expectations, and test coverage.

## Livewire 4 References

Use the current Livewire 4 docs when implementing:

- Pages and `Route::livewire()`: https://livewire.laravel.com/docs/4.x/pages
- Layout attribute: https://livewire.laravel.com/docs/4.x/attribute-layout
- Navigate and `wire:navigate`: https://livewire.laravel.com/docs/4.x/navigate
- `wire:navigate` reference: https://livewire.laravel.com/docs/4.x/wire-navigate


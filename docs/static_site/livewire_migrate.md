# Livewire 4 Migration Guide for Hulhudhuffaaru School

This document is a full migration guide for moving the current static school website into a Laravel + Livewire 4 application.

The goal is not to recreate the current HTML file by file. The goal is to preserve the site structure, content contracts, and user flows while moving the rendering and interactions into a maintainable server-driven architecture.

Relevant Livewire 4 documentation used for this guide:

- Upgrade guide: https://livewire.laravel.com/docs/4.x/upgrading
- Components: https://livewire.laravel.com/docs/4.x/components
- Pages: https://livewire.laravel.com/docs/4.x/pages
- Layout: https://livewire.laravel.com/docs/4.x/attribute-layout
- Navigate: https://livewire.laravel.com/docs/4.x/navigate
- `wire:navigate`: https://livewire.laravel.com/docs/4.x/wire-navigate
- Forms: https://livewire.laravel.com/docs/4.x/forms
- File uploads: https://livewire.laravel.com/docs/4.x/uploads
- Loading states: https://livewire.laravel.com/docs/4.x/loading-states
- `@persist`: https://livewire.laravel.com/docs/4.x/directive-persist
- `wire:transition`: https://livewire.laravel.com/docs/4.x/wire-transition

## 1. What You Are Migrating

The current site in `static_site/` is a static Tailwind prototype with page-specific HTML files and a large imperative `app.js`.

Current pages:

- `index.html`
- `about.html`
- `events.html`
- `event.html`
- `academics.html`
- `student-life.html`
- `gallery.html`
- `downloads.html`
- `admissions.html`
- `digital-services.html`
- `search.html`

Current JSON data contracts:

- `api/home.json`
- `api/about.json`
- `api/events.json`
- `api/academics.json`
- `api/student-life.json`
- `api/gallery.json`
- `api/downloads.json`
- `api/admissions.json`
- `api/digital-services.json`
- `api/search.json`

Current special backend contract:

- `endpoint.md` for the leaving certificate flow

The JSON files are already a strong UI contract. Preserve those shapes, even if the backend implementation changes.

## 2. Target Architecture

Use Laravel as the application host and Livewire 4 as the interactive rendering layer.

Recommended stack:

- Laravel for routing, controllers, storage, validation, and data access
- Livewire 4 for page rendering and stateful interactions
- Blade for layout and presentational components
- Eloquent models, DTOs, or API resources as the data translation layer

Recommended structure:

```text
app/
  Livewire/
    Pages/
    Components/
  Http/
    Controllers/
  Services/
  Data/
  ViewModels/
resources/
  views/
    layouts/
    livewire/
    components/
routes/
tests/
```

This keeps the site professional because each concern has one clear place:

- routes define the public surface
- layouts define the shell
- page components define page-level state
- smaller components define local interaction
- services and view models define data shaping

## 3. Migration Principles

Use these rules throughout the migration:

1. Keep the UI contracts stable.
2. Move rendering to Livewire pages.
3. Keep the shared shell centralized.
4. Use Blade components for static repeated UI.
5. Use Livewire only where state or server interaction is required.
6. Translate backend data into UI-ready shapes before it reaches the view.
7. Write tests around route rendering, component behavior, and API contracts.

Do not:

- keep browser-side JSON fetching as the main rendering path
- duplicate the navigation shell on every page
- turn every card into a Livewire component
- use raw database rows as view data
- mix shell logic into page content

## 4. Step-By-Step Migration

This is the actual migration order I would use.

### Step 1: Scaffold the Laravel app

Create or switch to the Laravel codebase first.

Then install Livewire 4:

```bash
composer require livewire/livewire:^4.0
php artisan optimize:clear
```

If the app is new, create the default Livewire layout:

```bash
php artisan livewire:layout
```

This is the step that must be included in the guide. It is the foundation before any page migration starts.

### Step 2: Decide the layout hierarchy

Create one main app layout for the entire public website.

Recommended default layout:

- `resources/views/layouts/app.blade.php`

This layout should contain:

- document `<head>`
- shared CSS and fonts
- top navigation
- mobile navigation
- search trigger
- main content slot
- footer
- persistent shell elements

For pages that need a different shell, use the Livewire `#[Layout]` attribute.

### Step 3: Define routes

Use routed Livewire page components for the public pages.

Example route map:

```php
Route::livewire('/', 'pages::home');
Route::livewire('/about', 'pages::about');
Route::livewire('/events', 'pages::events.index');
Route::livewire('/events/{event}', 'pages::events.show');
Route::livewire('/academics', 'pages::academics');
Route::livewire('/student-life', 'pages::student-life');
Route::livewire('/gallery', 'pages::gallery');
Route::livewire('/downloads', 'pages::downloads');
Route::livewire('/admissions', 'pages::admissions');
Route::livewire('/digital-services', 'pages::digital-services');
Route::livewire('/search', 'pages::search');
```

### Step 4: Move the data layer out of the browser

Replace `App.fetch('...')` and the `api/*.json` browser fetches with server-side data access.

Recommended translation path:

- database records
- repositories or query objects
- DTOs or view models
- Livewire page/component props
- Blade output

Keep the UI contract stable by mapping backend data into the same field names the current site already expects.

### Step 5: Build the shell first

Move the current navigation, search trigger, and footer into shared layout or shell components.

This gives you:

- one navigation source of truth
- one active-link behavior
- one mobile menu
- one search entry point
- one footer

### Step 6: Migrate the home page

Move `index.html` to a Livewire page component first.

Reason:

- it exercises the shell
- it exercises the content contract
- it uses hero state, quick links, featured events, testimonials, and contact form markup

### Step 7: Migrate the content pages

Migrate the content-heavy pages next:

- About
- Academics
- Student Life
- Gallery
- Downloads

These are mostly read-only pages and are good candidates for Blade components within a Livewire page.

### Step 8: Migrate Events

Events should become:

- an index page with filters
- a detail page with attachments and event contact

This is one of the best places to use Livewire state because filtering and detail rendering are both user-visible and interactive.

### Step 9: Migrate Digital Services

This page needs a more deliberate split because it has the most state.

Break it into:

- a page component
- a resource directory component
- a calendar component
- a leaving certificate request component
- a leaving certificate verification component

### Step 10: Migrate Search

Move the search spotlight into a persisted component mounted in the shell.

This preserves search across navigation and makes the experience feel like one application rather than disconnected pages.

### Step 11: Remove the old static renderer

Once the Livewire pages match the content and the interaction flows are stable, retire the browser-side page renderer and the fetch-driven HTML scripts.

## 5. Page Structure By Section

This section explains how the public site should be laid out in the new app.

### Home

Current source: `api/home.json`

Recommended structure:

1. hero carousel
2. stats strip
3. quick access grid
4. featured events
5. principal message
6. testimonials
7. contact section

Implementation notes:

- make the carousel a dedicated Livewire component if it has timers or transitions
- use `wire:transition` for slide swaps
- make quick links use `wire:navigate`

### About

Recommended structure:

1. intro hero
2. history
3. mission and vision
4. leadership
5. achievements
6. campus or facilities

This page should read like a polished institutional profile.

### Events

Current source: `api/events.json`

Recommended structure:

1. page header
2. status filters
3. event cards
4. event detail route
5. attachments and contact panel

The data contract already contains:

- `status`
- `dateStart`
- `dateEnd`
- `location`
- `coverImageUrl`
- `attachments`
- optional `contact`

### Academics

Recommended structure:

1. curriculum overview
2. junior school
3. senior school
4. subject areas
5. learning support
6. exam preparation or results links

### Student Life

Recommended structure:

1. student life overview
2. houses
3. clubs and societies
4. sports
5. arts and culture
6. welfare and support

### Gallery

Recommended structure:

1. gallery header
2. album filters
3. image grid
4. lightbox or detail view

### Downloads

Recommended structure:

1. downloads header
2. document groups
3. search or filter if needed
4. file cards or list rows

### Admissions

Recommended structure:

1. admissions overview
2. requirements
3. process steps
4. application form
5. upload fields if required
6. submission confirmation

### Digital Services

Current source: `api/digital-services.json`

Recommended structure:

1. services tab
2. resources tab
3. academic calendar tab
4. leaving certificate request flow
5. leaving certificate verification flow

This page needs the most separation of concerns.

### Search

Current source: `api/search.json`

Recommended structure:

1. search trigger in shell
2. persisted search overlay
3. query input
4. grouped results
5. empty state suggestions

## 6. Component Strategy

Use the simplest component type that fits the job.

### Livewire page components

Use these for routed screens:

- Home
- About
- Events index
- Event show
- Academics
- Student Life
- Gallery
- Downloads
- Admissions
- Digital Services
- Search

### Livewire interactive components

Use these only for actual state:

- hero carousel
- search overlay
- filters
- tabs
- leaving certificate flows
- upload-heavy forms

### Blade components

Use these for presentational building blocks:

- section header
- stat tile
- card header
- event card
- download row
- resource card
- contact block
- badge
- icon button

This is what makes the site look professional in implementation terms: the shell stays consistent, repeated UI stays composable, and only real state lives in Livewire.

## 7. Shell Design

The shell should be the same on every page.

Shell responsibilities:

- brand header
- primary navigation
- mobile navigation
- search button
- footer
- optional announcements bar
- persistent overlays

Use `wire:navigate` on all internal links.

Use `data-current` styling for active nav states.

Use `@persist` for shell elements that should survive page changes, especially search and any future media player or notification region.

## 8. Data Contract Mapping

Treat the current JSON files as frontend-facing schemas.

Examples:

- `home.json` maps to home page hero, stats, quick links, testimonials, contact
- `events.json` maps to event card lists and event detail pages
- `digital-services.json` maps to resources and calendar sections
- `search.json` maps to grouped query results and empty-state suggestions

Recommended backend mapping:

- `App\Services\HomePageData`
- `App\Services\EventDirectory`
- `App\Services\DigitalServicesData`
- `App\Services\SearchIndex`
- `App\DTO\...` or `App\ViewModels\...`

Never allow the view to depend on a raw database record shape if the UI expects a curated public schema.

## 9. Livewire 4 Choices That Matter Here

For this migration, these features are the most relevant:

- `Route::livewire()` for page routing
- `#[Layout]` for page-specific layouts when needed
- class-based components for a conventional Laravel structure
- `wire:navigate` for SPA-like navigation
- `@persist` for preserved shell elements
- `wire:transition` for polished content changes
- form objects for heavier forms
- file upload support for admissions or other document flows
- loading states for requests and submissions

Use class-based components as the default because this is a migration from an existing site and not a greenfield Livewire-only app.

## 10. Feature Mapping From The Current Site

This is the practical mapping from the current static implementation.

### `app.js`

Current role:

- navigation rendering
- mobile menu toggling
- search overlay
- date formatting
- icon rendering
- loading and error states
- page bootstrap

Livewire replacement:

- shell layout
- Blade icon components or SVG partials
- Livewire search overlay
- Livewire loading states
- server-side date formatting through presenters or helpers

### `index.html`

Current role:

- hero carousel
- stats
- quick links
- featured events
- principal message
- testimonials
- contact form

Livewire replacement:

- `Home` page component
- `HeroCarousel` component
- stat and card Blade components
- contact form component if submission is needed

### `events.html` / `event.html`

Current role:

- event listing
- event filtering
- detail and attachment display

Livewire replacement:

- `Events/Index`
- `Events/Show`
- filter state in Livewire

### `digital-services.html`

Current role:

- tab switching
- resource filtering
- academic calendar
- leaving certificate request and verification

Livewire replacement:

- `DigitalServices`
- `ResourceDirectory`
- `AcademicCalendar`
- `LeavingCertificateRequest`
- `LeavingCertificateVerification`

### `search.html`

Current role:

- query input
- results list
- empty state suggestions

Livewire replacement:

- shell-mounted search overlay
- debounced query state
- grouped results by section

## 11. Leaving Certificate Flow

This is the most sensitive workflow in the site because it mixes user input, verification, and a protected file link.

Current contract source: `endpoint.md`

Rules to keep:

- the frontend should not expose a private storage path
- the backend should return a browser-openable `pdf_url`
- the file might be PDF, JPG, PNG, or another approved format
- the public route may be signed or protected
- handle `422`, `403`, `404`, and `429` cleanly

Recommended Livewire breakdown:

- step 1: request OTP
- step 2: enter OTP
- step 3: receive file link
- step 4: verify printed code and show file link

Keep the request and verification states in separate components if the logic becomes too dense.

## 12. Forms And Uploads

Use Livewire forms for all user submission flows.

Good candidates:

- admissions application
- contact form
- certificate request
- certificate verification

If the admissions flow includes document uploads, use Livewire's file upload support and server-side validation.

Recommended behavior:

- validate server-side
- show loading state during submission
- preserve form state on validation errors
- redirect or emit success state after submission

## 13. Testing Plan

Professional migration work needs tests.

Test these categories:

1. route rendering
2. layout rendering
3. page component data loading
4. filtering and search behavior
5. form validation and submission
6. certificate flow success and error states
7. file upload behavior if used

Minimum expected tests:

- home page renders with expected content sections
- events index filters by status
- event detail page displays attachments
- search returns grouped results
- digital services tabs switch correctly
- certificate request flow handles successful OTP requests
- certificate verification returns a displayable link

Also test the contract layer directly so the view data does not drift.

## 14. Deployment And Rollout

Do not cut over the whole site at once if you do not have to.

Recommended rollout:

1. ship the new shell
2. migrate home
3. migrate the content pages
4. migrate events
5. migrate digital services
6. migrate search
7. remove the old static front-end rendering path

This makes the migration easier to review and easier to revert if a page misbehaves.

## 15. Suggested Final File Structure

One professional end state would look like this:

```text
app/
  Livewire/
    Pages/
      Home.php
      About.php
      Academics.php
      StudentLife.php
      Gallery.php
      Downloads.php
      Admissions.php
      DigitalServices.php
      Search.php
      Events/
        Index.php
        Show.php
    Components/
      HeroCarousel.php
      SearchOverlay.php
      EventFilter.php
      ResourceDirectory.php
      AcademicCalendar.php
      LeavingCertificateRequest.php
      LeavingCertificateVerification.php
  Services/
  Data/
  ViewModels/
resources/
  views/
    layouts/
      app.blade.php
    components/
    livewire/
routes/
tests/
```

That structure is professional because it keeps the public website organized by responsibility rather than by one-off HTML files.

## 16. Bottom Line

The right Livewire 4 migration here is:

- Laravel for app structure and data access
- Livewire page components for routable pages
- Blade components for reusable presentation
- focused Livewire islands for actual interactivity
- a single shared shell
- contract-driven data translation

If you keep the JSON shapes, centralize the layout, and split interactive state into focused components, the site will feel clean and maintainable instead of patched together.

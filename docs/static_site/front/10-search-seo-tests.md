# Search, SEO, And Tests

This document covers cross-page frontend behavior for the Livewire 4 public website.

## Search

Route:

```text
GET /search
```

Livewire page:

```text
app/Livewire/Pages/Search.php
resources/views/livewire/pages/search.blade.php
```

Optional persisted shell component:

```text
app/Livewire/Components/SearchOverlay.php
```

## Search Sources

Search should read from public, active records only:

- events
- documents
- albums
- resources
- pages with static route metadata
- courses
- achievements if desired

Initial search can use simple SQL `LIKE` queries. Full-text indexing can come later if content grows.

## Search Result Shape

Normalize all results before rendering:

```php
[
    'type' => 'event',
    'title' => 'Open Day 2026',
    'description' => 'Tour the school and meet teachers.',
    'href' => route('events.show', $event),
]
```

Group results by type in the UI.

## Persisted Overlay

If the search overlay is placed in the shell, wrap it with `@persist` so it survives `wire:navigate` page changes.

Use `data-current` classes for active nav links instead of request-only checks inside persisted navigation.

## SEO Defaults

Every page should define:

- title
- meta description
- canonical URL
- Open Graph title
- Open Graph description
- Open Graph image fallback

Suggested source for fallback values:

- `SchoolSetting.school_name`
- `SchoolSetting.school_tagline`
- `SchoolSetting.hero_image_url`

## Accessibility

- Use semantic landmarks: header, nav, main, footer.
- All form inputs need labels.
- Icon-only buttons need accessible names.
- Loading states should not remove form context.
- Links that open external sites should use `target="_blank"` and `rel="noopener noreferrer"` when a new tab is required.
- Error messages should be visible near the affected field.

## Feature Tests

Route rendering:

- home page renders with active slides
- about page renders mission/history sections
- academics page renders levels and courses
- admissions page renders settings and slots
- digital services page renders resources and calendar
- events index filters by status
- event detail renders attachments
- downloads filters by category, audience, and search
- gallery filters by category
- student life renders related staff names

Livewire behavior:

- contact form validates and creates a contact submission
- admissions form validates and creates an application submission
- application tracker hides internal notes
- leaving certificate request handles success and validation errors
- leaving certificate verification handles revoked and rate-limit responses
- search returns grouped public results only

## Browser Checks

Before launch, check:

- mobile nav opens and closes
- `wire:navigate` links update content and active states
- forms show loading states
- dark mode is readable if implemented
- file links open in the browser
- certificate links are display URLs returned by the backend
- pages remain readable with missing optional content


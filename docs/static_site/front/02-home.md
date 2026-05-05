# Home Page Frontend

Based on `cms/01-index.md`.

Route:

```text
GET /
```

Livewire page:

```text
app/Livewire/Pages/Home.php
resources/views/livewire/pages/home.blade.php
```

## Sections

Render these sections in order:

1. hero carousel from active `Slide` records
2. stats strip from active `Stat` records
3. quick links from active `QuickLink` records
4. featured events from active `Event` records
5. principal message from `Staff` role `principal`
6. testimonials from active `Testimonial` records
7. contact form

## Data Queries

```php
$slides = Slide::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$stats = Stat::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$quickLinks = QuickLink::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();

$principal = Staff::query()
    ->where('is_active', true)
    ->where('role', 'principal')
    ->orderBy('sort_order')
    ->first();

$featuredEvents = Event::query()
    ->where('is_active', true)
    ->orderBy('date_start')
    ->limit(3)
    ->get();

$testimonials = Testimonial::query()
    ->where('is_active', true)
    ->orderBy('sort_order')
    ->get();
```

## Livewire Components

Use Livewire where there is state:

- `HeroCarousel` if the hero supports active slide changes.
- `ContactForm` for validation and submission.

Use Blade components for:

- stat tiles
- quick link cards
- event cards
- testimonial cards
- section headers

## Contact Form

The form writes to `contact_submissions`.

Fields:

- `name`, required string
- `email`, required email
- `message`, required string

Behavior:

- validate on submit
- create the submission with `is_read = false`
- show a clear success state
- throttle repeated submissions by IP or session
- do not expose CMS inbox behavior

## Empty States

- If no slides exist, render a static school-branded hero using `SchoolSetting.hero_image_url` as the image fallback.
- If no featured events exist, hide the featured events section.
- If no principal exists, hide the principal section.
- If no testimonials exist, hide the testimonials section.

## SEO

Default title:

```text
Hulhudhuffaaru School
```

Default description should come from `school_tagline` or a public homepage summary.


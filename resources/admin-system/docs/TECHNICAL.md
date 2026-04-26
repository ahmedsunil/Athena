# Technical Documentation
## Admin UI System — Design Tokens

---

## Overview

The design token system is a set of PHP files that return arrays of Tailwind CSS class strings. Each file covers one aspect of the UI: colors, spacing, typography, radius, shadows, and component-level class bundles.

These files are **reference documents** — you read them to know what class to use. You paste the class string directly into your Blade templates.

---

## File Reference

### `tokens/colors.php`

Returns the color palette organized by semantic role.

#### Structure

```php
[
    'primary'  => ['light', 'base', 'dark', 'accent'],
    'neutral'  => ['bg', 'surface', 'subtle', 'muted', 'border', 'divider', 'input'],
    'text'     => ['primary', 'secondary', 'body', 'muted', 'hint', 'faint', 'inverse'],
    'status'   => ['success', 'warning', 'error', 'info'],  // each has bg/text/ring
    'danger'   => ['light', 'base', 'dark', 'muted', 'ring'],
    'focus'    => ['border', 'ring'],
]
```

#### How to use

```php
// In a ServiceProvider or helper:
$colors = require resource_path('admin-system/tokens/colors.php');

// Or just read the file and copy the class directly into your Blade:
// e.g. colors.php says primary.base = 'teal-600'
// So your button becomes: class="bg-teal-600 hover:bg-teal-700"
```

---

### `tokens/spacing.php`

Returns spacing class groups for consistent padding and gaps.

#### Key Tokens

| Key | Value | Where Used |
|-----|-------|-----------|
| `page.padding` | `px-4 py-6` | Main content wrapper |
| `card.padding` | `p-5` | All card bodies |
| `table.cell` | `px-5 py-3` | Table td/th |
| `modal.padding` | `p-6` | Modal containers |
| `form.field_gap` | `space-y-4` | Form field vertical spacing |
| `form.label_gap` | `mb-1.5` | Between label and input |
| `inline.sm` | `gap-1.5` | Icon + label in badges/nav |
| `inline.lg` | `gap-2.5` | Nav item icon + text |

---

### `tokens/typography.php`

Returns font size, weight, tracking, and preset style strings.

#### Preset Styles (most useful)

| Key | Tailwind Classes |
|-----|----------------|
| `styles.page_title` | `text-lg font-bold text-stone-900 sm:text-xl` |
| `styles.card_header` | `text-sm font-semibold text-stone-700` |
| `styles.section_label` | `text-xs font-medium text-stone-700` |
| `styles.body` | `text-sm text-stone-600` |
| `styles.muted` | `text-xs text-stone-400` |
| `styles.stat_value` | `text-lg font-bold text-stone-900` |
| `styles.stat_label` | `text-xs font-medium text-stone-500` |
| `styles.table_header` | `text-xs font-medium text-stone-400` |
| `styles.table_cell` | `text-sm text-stone-700` |
| `styles.badge` | `text-xs font-medium` |
| `styles.error` | `text-xs text-red-600` |
| `styles.nav_group` | `text-[10px] font-semibold uppercase tracking-widest text-stone-400` |

---

### `tokens/radius.php`

Returns border radius tokens.

| Key | Class | Used For |
|-----|-------|---------|
| `md` | `rounded-md` | Dropdown search inputs |
| `lg` | `rounded-lg` | Inputs, buttons, small containers |
| `xl` | `rounded-xl` | Cards, tables, filter bars |
| `2xl` | `rounded-2xl` | Large modals |
| `full` | `rounded-full` | Badges, toggle switches |
| `t_sm` | `rounded-t-sm` | Chart bar tops |

---

### `tokens/shadows.php`

Returns shadow tokens.

| Key | Class | Used For |
|-----|-------|---------|
| `card` | `shadow-sm` | Cards, tables, stat cards |
| `dropdown` | `shadow-lg` | Dropdowns, toasts |
| `modal` | `shadow-xl` | Modals |

---

### `tokens/components.php`

The most important file. Returns complete Tailwind class strings for every component variant.

#### Components Covered

| Key | What It Is |
|-----|-----------|
| `button.primary` | Teal action button |
| `button.secondary` | White bordered button |
| `button.danger` | Red delete button |
| `button.ghost` | Text-only neutral action |
| `button.ghost_primary` | Text-only teal link action |
| `button.ghost_danger` | Text-only red delete link |
| `card.base` | Standard white card with padding |
| `card.compact` | Card with tighter vertical padding |
| `card.table` | Card wrapper for tables (overflow-hidden) |
| `stat_card.*` | Metric card anatomy (wrapper/icon/label/value) |
| `badge.*` | Status pill variants |
| `table.*` | Table wrapper, head row, body row, td, th, actions |
| `form.*` | Input, select, textarea, checkbox, label, error, file |
| `toggle.*` | Toggle switch track (on/off) and knob |
| `modal.*` | Overlay, box, title, body, footer |
| `toast.*` | Wrapper, item, success/error variants |
| `empty_state.*` | Wrapper, icon, message |
| `filter_bar.*` | Full filter bar anatomy |
| `page_header.*` | Wrapper, title, subtitle |
| `nav.*` | Sidebar nav item, active, inactive, group label |
| `searchable_dropdown.*` | Trigger, panel, search, list, item |
| `bulk_action_bar.*` | Wrapper, label, button |

---

## Z-Index Layers

| Layer | Z-Index | Used For |
|-------|---------|---------|
| Mobile topbar | `z-40` | Fixed mobile header |
| Modals/drawers | `z-50` | Overlays |
| Dropdown overlay | `z-[25]` | Click-away handler |
| Dropdown panel | `z-[30]` | Dropdown content |
| Toasts | `z-[200]` | Always on top |

---

## Responsive Breakpoints

| Breakpoint | Width | Behavior |
|-----------|-------|---------|
| (default) | 0px+ | Mobile: single column, card lists, no sidebar |
| `sm:` | 640px+ | Wider padding, 2-col form grids, slightly larger text |
| `md:` | 768px+ | Sidebar visible, mobile bar hidden, tables visible |
| `lg:` | 1024px+ | 3-col grids for stat cards |

---

## Animation & Transition Patterns

### Hover transitions
```html
class="transition-colors hover:bg-teal-700"
```

### Alpine.js show/hide (modals, toasts, drawers)
```html
x-transition:enter="transition ease-out duration-150"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-100"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
```

### Toggle switch transition
```html
class="transition-colors duration-200"   ← track
class="transition-transform duration-200" ← knob
```

---

## Coding Standards (PSR-12)

| Element | Standard | Example |
|---------|---------|---------|
| Class Names | PascalCase | `CertificateGenerator` |
| Method Names | camelCase | `generatePdf()` |
| Constants | UPPER_CASE | `VERSION_NUMBER` |
| Properties/Variables | camelCase | `$recipientName` |
| Blade components | kebab-case | `<x-stat-card>` |
| Livewire components | PascalCase (class) | `SalesList` |
| View files | kebab-case | `sales-list.blade.php` |

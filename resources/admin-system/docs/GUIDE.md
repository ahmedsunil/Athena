# Developer Guide
## How to Read, Use, and Customize the Admin UI System

---

## Reading This System for the First Time

You have 3 types of files:

| Type | Location | What It Does |
|------|---------|-------------|
| Token files | `tokens/*.php` | Tell you WHAT class to use for every UI element |
| Blade components | `components/**/*.blade.php` | Pre-built HTML snippets you `<x-include>` in your views |
| Skeleton templates | `skeletons/*.blade.php` | Full page starters you copy and fill in |

**Start with `tokens/components.php`** — it is the single most useful file. It has the exact Tailwind class string for every component variant. Open it and Ctrl+F whatever you need.

---

## How to Use a Token

### Step 1 — Open the token file

Example: you want to know what class to use for a primary button.

Open `tokens/components.php` and find:

```php
'button' => [
    'primary' => 'inline-flex items-center gap-1.5 rounded-lg bg-teal-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700',
```

### Step 2 — Copy the string into your Blade file

```html
<button class="inline-flex items-center gap-1.5 rounded-lg bg-teal-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
    Save
</button>
```

That's it. You do not need to `require()` or `import` anything — these files are reference documents you read, not runtime dependencies.

---

## How to Use a Blade Component (Phase 3 and beyond)

Once Phase 3 is complete, components will be under `components/`. You register them in a ServiceProvider and use them with Blade's `<x-component-name>` syntax.

Example for a card:

```html
<x-admin.card>
    Content goes here
</x-admin.card>
```

Example for a stat card:

```html
<x-admin.stat-card
    label="Total Sales"
    value="RM 12,400"
    secondary="+8% this month"
    icon="sales"
    color="teal"
/>
```

---

## How to Customize a Component

### Change a color

The primary color is `teal`. To change it system-wide:

1. Open `tokens/colors.php`
2. Find the `primary` section
3. Update the class names:

```php
// Before (teal)
'primary' => [
    'light' => 'teal-50',
    'base'  => 'teal-600',
    'dark'  => 'teal-700',
],

// After (indigo)
'primary' => [
    'light' => 'indigo-50',
    'base'  => 'indigo-600',
    'dark'  => 'indigo-700',
],
```

4. Then update `tokens/components.php` — find/replace `teal-` with `indigo-` in the relevant component entries.
5. Then update your Blade components (once Phase 3 is done) to use the new classes.

> **Important**: Tailwind's purge/JIT engine requires class strings to appear verbatim in files it scans. If you swap colors, make sure the new color classes (e.g. `indigo-600`) appear in at least one scanned file, or add them to your `tailwind.config.js` safelist.

---

### Change the card border radius

1. Open `tokens/radius.php`
2. Change `xl` from `rounded-xl` to `rounded-2xl` (for more rounding)
3. Open `tokens/components.php` and update all entries that use `rounded-xl` to `rounded-2xl`

---

### Change card padding

1. Open `tokens/spacing.php`
2. Change `card.padding` from `p-5` to `p-6`
3. Update `tokens/components.php` — find `'base' => '...p-5...'` under `card` and change it
4. Update any Blade components in `components/ui/card.blade.php`

---

### Add a new button variant

1. Open `tokens/components.php`
2. Add a new key under `button`:

```php
'button' => [
    ...
    'warning' => 'inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-amber-600',
],
```

3. Use it in your Blade:

```html
<button class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-amber-600">
    Approve
</button>
```

---

### Add a new status badge color

1. Open `tokens/components.php`
2. Add under `badge`:

```php
'badge' => [
    ...
    'info' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
],
```

3. In your Blade:

```html
<span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset bg-blue-50 text-blue-700 ring-blue-600/20">
    Draft
</span>
```

---

## How to Build a New CRUD Feature

### Using the skeleton templates (Phase 4)

Once Phase 4 is complete, copy the relevant skeleton:

```bash
cp resources/admin-system/skeletons/index.blade.php resources/views/livewire/orders/orders-list.blade.php
cp resources/admin-system/skeletons/form.blade.php  resources/views/livewire/orders/order-form.blade.php
cp resources/admin-system/skeletons/show.blade.php  resources/views/livewire/orders/order-detail.blade.php
```

Then replace the placeholder text:
- `[PageTitle]` → `Orders`
- `[ResourceName]` → `order`
- `[columns]` → your actual table columns
- `[fields]` → your actual form fields

---

## File Map — Where to Find Things

| I want to... | Look in... |
|-------------|-----------|
| Find the class for a primary button | `tokens/components.php` → `button.primary` |
| Find the class for a card container | `tokens/components.php` → `card.base` |
| Find the class for a status badge | `tokens/components.php` → `badge.*` |
| Find the class for a form input | `tokens/components.php` → `form.input` |
| Find page padding | `tokens/spacing.php` → `page.padding` |
| Find card padding | `tokens/spacing.php` → `card.padding` |
| Find what color to use | `tokens/colors.php` |
| Find font sizes and text styles | `tokens/typography.php` → `styles.*` |
| Find border radius | `tokens/radius.php` |
| Find shadows | `tokens/shadows.php` |
| See what components exist | `tokens/components.php` (all keys) |
| Build a full page | `skeletons/*.blade.php` (Phase 4) |

---

## Common Patterns Quick Reference

### Page with title, count, and add button

```html
<div class="mb-4 flex flex-wrap items-center justify-between gap-3 sm:mb-6">
    <div>
        <h1 class="text-lg font-bold text-stone-900 sm:text-xl">Orders</h1>
        <p class="text-xs text-stone-500 sm:text-sm">{{ $total }} orders found</p>
    </div>
    <a href="{{ route('orders.create') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-teal-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-teal-700">
        <svg class="h-4 w-4"><!-- plus icon --></svg>
        New Order
    </a>
</div>
```

### Basic white card

```html
<div class="rounded-xl border border-stone-200 bg-white p-5 shadow-sm">
    Content
</div>
```

### Table wrapper

```html
<div class="overflow-hidden rounded-xl border border-stone-200 bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-stone-100 text-left text-xs font-medium text-stone-400">
                <th class="px-5 py-3">Name</th>
                <th class="px-5 py-3">Status</th>
                <th class="px-5 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-stone-100 transition-colors odd:bg-white even:bg-stone-50/50 hover:bg-teal-50/30 last:border-0">
                <td class="px-5 py-3 text-stone-700">Item Name</td>
                <td class="px-5 py-3">
                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset bg-teal-50 text-teal-700 ring-teal-600/20">Paid</span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="#" class="text-xs font-medium text-stone-500 hover:text-stone-700">Edit</a>
                        <button class="text-xs font-medium text-red-400 hover:text-red-600">Delete</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
```

### Form field

```html
<div>
    <label class="mb-1.5 block text-xs font-medium text-stone-700">Customer Name *</label>
    <input
        type="text"
        wire:model="customerName"
        class="w-full rounded-lg border border-stone-300 px-3 py-2 text-sm text-stone-700 focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500"
    >
    @error('customerName')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### Confirmation modal (Livewire)

```html
@if ($showDeleteModal)
<div class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/30 backdrop-blur-sm"
     wire:click.self="$set('showDeleteModal', false)">
    <div class="w-full max-w-sm rounded-2xl border border-stone-200 bg-white p-6 shadow-xl">
        <h3 class="text-base font-semibold text-stone-900">Delete Item?</h3>
        <p class="mt-2 text-sm text-stone-500">This action cannot be undone.</p>
        <div class="mt-5 flex justify-end gap-2">
            <button wire:click="$set('showDeleteModal', false)"
                    class="rounded-lg border border-stone-200 px-4 py-2.5 text-center text-sm font-medium text-stone-600 transition-colors hover:bg-stone-50">
                Cancel
            </button>
            <button wire:click="deleteItem"
                    class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition-colors hover:bg-red-700">
                Delete
            </button>
        </div>
    </div>
</div>
@endif
```

### Empty state

```html
<div class="py-12 text-center">
    <svg class="mx-auto mb-3 h-8 w-8 text-stone-300"><!-- icon --></svg>
    <p class="text-sm font-medium text-stone-400">No orders found</p>
</div>
```

---

## Do's and Don'ts

### Do
- Copy class strings directly from the token files
- Use `tokens/components.php` as your primary reference
- Follow the mobile-first pattern: start with mobile layout, add `md:` for desktop
- Use `wire:model`, `wire:click`, `wire:submit` for Livewire interactions
- Add `@error('field')` validation messages after every input

### Don't
- Don't invent new colors that aren't in the token files — it will break consistency
- Don't modify `resources/views/` in the original Veya project
- Don't add shadows heavier than `shadow-xl` — it will look out of place
- Don't use font sizes smaller than `text-[10px]` — already the minimum used
- Don't add border radius larger than `rounded-2xl` for content, or `rounded-full` for pills

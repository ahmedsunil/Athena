# Product Requirements Document (PRD)
## Admin UI System — `livewire4_skeleton`

---

## 1. Purpose

This project extracts the design system of an existing Laravel + Livewire admin panel (Veya) into a **reusable starter kit**. The goal is to produce a set of components, tokens, layouts, and page templates that can be dropped into any new project and produce a consistent, production-quality admin UI — without rebuilding it from scratch each time.

---

## 2. Problem Being Solved

Every new Laravel admin project required rebuilding the same UI elements: cards, tables, forms, modals, badges, filter bars. This took time and produced inconsistencies between projects. This system extracts those patterns once, cleanly, so they can be reused.

---

## 3. Scope

### In Scope
- Design tokens (colors, spacing, typography, radius, shadows, component classes)
- Blade components (layout, UI, forms, tables)
- Page skeleton templates (dashboard, list, form, detail)
- Full documentation

### Out of Scope
- Backend logic (no PHP business logic, no Eloquent models)
- Authentication system
- API integrations
- JavaScript/Alpine.js logic (only Tailwind classes)

---

## 4. Target Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Framework | Laravel | 13.3.0 |
| UI | Livewire | 4.2.4 |
| Runtime | PHP | 8.4 |
| Styling | Tailwind CSS | (from source project) |
| JS | Alpine.js | (from source project) |

---

## 5. Output Structure

```
resources/admin-system/
├── docs/                  ← You are here
│   ├── PRD.md
│   ├── TECHNICAL.md
│   └── GUIDE.md
├── tokens/                ← Design token files
│   ├── colors.php
│   ├── spacing.php
│   ├── typography.php
│   ├── radius.php
│   ├── shadows.php
│   └── components.php
├── components/            ← Blade components (Phase 3)
│   ├── layout/
│   ├── ui/
│   ├── forms/
│   └── tables/
├── layouts/               ← App layout files (Phase 3)
└── skeletons/             ← Page templates (Phase 4)
    ├── dashboard.blade.php
    ├── index.blade.php
    ├── form.blade.php
    └── show.blade.php
```

---

## 6. Design Principles

1. **Extract, don't invent** — every token and class comes directly from the source project
2. **Non-destructive** — the source project is never touched
3. **Consistency** — same spacing, colors, and radius across all components
4. **Responsiveness** — mobile-first, cards on mobile, tables on md+
5. **Livewire-compatible** — all components work with `wire:` attributes

---

## 7. Phases

| Phase | Deliverable | Status |
|-------|------------|--------|
| 1 | UI Audit (no files) | Complete |
| 2 | Design Token Files | Complete |
| 3 | Blade Components | Pending |
| 4 | Page Skeletons | Pending |

---

## 8. Color System Summary

| Role | Tailwind Class |
|------|---------------|
| Primary action | `teal-600` |
| Primary hover | `teal-700` |
| Primary light bg | `teal-50` |
| Page background | `stone-50` |
| Surface (cards) | `white` |
| Primary text | `stone-900` |
| Muted text | `stone-400` / `stone-500` |
| Border | `stone-200` |
| Warning | `amber-50` / `amber-700` |
| Danger | `red-50` / `red-600` |

---

## 9. Typography Summary

- **Body font**: Inter (sans-serif)
- **Data/numbers font**: IBM Plex Mono
- **Base size**: `text-sm` (14px)
- **Page titles**: `text-lg sm:text-xl font-bold text-stone-900`
- **Labels**: `text-xs font-medium text-stone-700`
- **Muted text**: `text-xs text-stone-400`

---

## 10. Success Criteria

- A developer can start a new Laravel admin project and copy `resources/admin-system/` in
- They can build a new CRUD feature (list + form + detail) using only the skeleton templates and components
- The result is visually identical to the source project
- No existing Veya files are modified

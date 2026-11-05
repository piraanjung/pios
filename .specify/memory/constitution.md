# PIOS Smart City — UI/UX Constitution

> **Status:** Active & Ratified  
> **Authority:** Supersedes ad-hoc frontend changes across all modules and Vertical Slices  
> **Source Documents:** [MASTER.md](../../design-system/pios-smart-city/MASTER.md), [ADR-0005](../../docs/adr/0005-soft-ui-dashboard-pro-design-system-standardization.md), [ADR-0008](../../docs/adr/0008-standardized-api-response-envelope.md), [ADR-0009](../../docs/adr/0009-standardizing-typography-with-kanit-font.md)

---

## 1. Core Principles (Non-Negotiable)

### Principle 1: Single Source of Truth for Design Tokens
- Colors, spacing, shadows, and radii MUST strictly derive from CSS custom properties defined in `resources/css/tokens.css` and documented in `design-system/pios-smart-city/MASTER.md`.
- Never hardcode arbitrary hex colors (`#...`) or raw pixel dimensions inside Blade templates.
- Any module-level variation MUST be backed by a verified page override in `design-system/pios-smart-city/pages/*.md`.

### Principle 2: Thai-First Accessible Typography (ADR-0009)
- All user-facing views MUST standardize on **Kanit** as the primary font family with **Open Sans** Latin fallback via `@include('layouts.partials.head-fonts')`.
- Strictly enforce font weight hierarchy:
  - `700 (Bold)`: KPI values, big statistics
  - `600 (Semi-Bold)`: Page titles, card headers, modal titles
  - `500 (Medium)`: Buttons, badges, navigation links, table headers
  - `400 (Regular)`: Body copy, form inputs, table cells
  - `300 (Light)`: Caption, metadata, helper text
- Always include `font-display: swap` to prevent FOUT (Flash of Unstyled Text).

### Principle 3: Accessibility Baseline (WCAG 2.2 AA)
- Text contrast ratio MUST achieve at least 4.5:1 against its background.
- Focus rings MUST be clearly visible for all interactive elements during keyboard navigation (`:focus-visible`).
- Every icon-only button MUST include a meaningful `aria-label` or `.visually-hidden` text.
- Micro-interactions and animations MUST respect `prefers-reduced-motion: reduce`.
- Touch targets on mobile and AI Kiosk touchscreens MUST measure at least `48px` (kiosk: `64px`).

### Principle 4: Reusable Component-First Assembly
- All pages MUST be assembled using standard Blade components in `resources/views/components/soft-ui/` (`card`, `stat-card`, `button`, `badge`, `alert`, `modal`, `datatable`).
- Do not repeat inline card, button, or badge markup. If a new pattern emerges, create a well-tested Blade component first.

### Principle 5: Vertical Slice Alignment & Master Layouts
- Slices own their views and domain view models, but MUST inherit from one of the centralized Master Layouts (`layouts.soft-ui-pro`, `layouts.mobile`, `layouts.kiosk`, `layouts.auth`, `layouts.print`).
- Redundant legacy layouts MUST be transitioned via `@extends('layouts.soft-ui-pro')` to prevent layout drift.

### Principle 6: Standardized API Response Envelope (ADR-0008)
- Every UI component interacting with backend endpoints via AJAX/Fetch/Axios MUST expect and gracefully handle the 3-key envelope: `{ success: boolean, data: any, errors: any }`.
- Every dynamic view MUST implement explicit **Empty**, **Loading**, and **Error** states.

---

## 2. Quality Gates & Definition of Done

Before any UI pull request or task can be claimed complete, it MUST satisfy:
1. **PHPUnit UI Test Suite:** 100% passing tests in `tests/Feature/Ui/`.
2. **Playwright E2E & Visual Verification:** Passing runs across Desktop (1440x900), Mobile (390x844), and Kiosk (1080x1920) with no unhandled console errors or 404 assets.
3. **Automated Accessibility Audit:** Zero serious or critical violations reported by axe-core.
4. **Visual Baseline Refresh:** Updated screenshots captured and stored in `docs/screenshots/`.

---

## 3. Governance

- Amendments to this Constitution require updating `MASTER.md`, issuing or amending an ADR in `docs/adr/`, and verifying regression test suites.
- Any deviation during `/speckit.plan` or `/speckit.tasks` must be flagged during `/speckit.analyze`.

**Version:** 1.0.0 | **Ratified:** 2026-11-05 | **Last Amended:** 2026-11-05

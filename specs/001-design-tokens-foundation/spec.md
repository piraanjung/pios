# Feature Specification: 001 Design Tokens Foundation

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md`

## 1. User Scenarios & Testing

### User Story 1 - Centralized Design Tokens (Priority: P1)
As a developer building municipal UI screens, I want all typography, colors, shadows, and radii to derive from unified CSS tokens (`resources/css/tokens.css` and `public/css/tokens.css`) so that changing a theme value updates all layouts simultaneously without modifying Blade code.

**Why this priority:** Fundamental baseline for all subsequent redesign slices.  
**Independent Test:** Modifying `--color-primary` in `tokens.css` immediately shifts the theme in `soft-ui-pro.blade.php`.

**Acceptance Scenarios:**
1. **Given** any layout rendering in the application, **When** inspecting root CSS, **Then** `--color-primary: #0f172a`, `--font-family-sans: 'Kanit'...'` are declared.
2. **Given** a user with `prefers-reduced-motion: reduce`, **When** any animated UI is triggered, **Then** animation duration drops to `0.01ms`.

---

### User Story 2 - Bounded Context Module Palette (Priority: P2)
As a local government staff member working across multiple modules (Tabwater, Waste, Welfare), I want distinct semantic accent colors per module so that I immediately recognize my current operational context.

**Acceptance Scenarios:**
1. **Given** Tabwater module, **When** viewing badges and active states, **Then** Cyan gradient (`--gradient-info`) is used.
2. **Given** RecycleBank module, **When** viewing badges, **Then** Emerald gradient (`--gradient-success`) is used.
3. **Given** AnnualTrash module, **When** viewing badges, **Then** Amber gradient (`--gradient-warning`) is used.
4. **Given** Welfare module, **When** viewing badges, **Then** Rose gradient (`--gradient-danger`) is used.

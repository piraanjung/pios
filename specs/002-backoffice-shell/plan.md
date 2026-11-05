# Implementation Plan: 002 Backoffice Shell & Navigation

**Specification Ref**: `specs/002-backoffice-shell/spec.md`  
**Design Intelligence**: `design-system/pios-smart-city/MASTER.md` & `pages/dashboard.md`  
**Status**: Ready

## 1. Technical Architecture & File Changes
- `resources/views/layouts/soft-ui-pro.blade.php`:
  - Enhance Navbar with `aria-label="การนำทางหลัก"` and `role="navigation"`.
  - Add explicit `aria-label` to hamburger and toggle icon buttons.
  - Update `.tenant-badge` to use token variables (`--gradient-primary`, `--radius-full`, `--font-weight-semibold`).
- `tests/Feature/Ui/BackofficeShellTest.php`:
  - Assert presence of accessibility attributes, tenant context rendering, and navbar blur classes.

## 2. Verification Strategy
- Run PHPUnit Ui testsuite.
- Playwright visual tests across Desktop and Mobile.

# Implementation Plan: 003 Backoffice Dashboard KPI & Charts

**Specification Ref**: `specs/003-backoffice-dashboard-kpi/spec.md`  
**Design Intelligence**: `design-system/pios-smart-city/MASTER.md` & `pages/dashboard.md`  
**Status**: Ready

## 1. Technical Architecture & Component
- Component: `resources/views/components/soft-ui/stat-card.blade.php`.
- Tokens: `--color-primary`, `--font-weight-bold`, `--font-weight-bolder`, `--radius-md`.
- Layout: Responsive Bootstrap 5 grid (`col-xl-3 col-sm-6`).

## 2. Verification Strategy
- PHPUnit component test `SoftUiComponentsTest.php`.
- Playwright responsive tests across 1440px and 390px viewports.

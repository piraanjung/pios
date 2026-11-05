# Implementation Plan: 004 Data Table Pattern

**Specification Ref**: `specs/004-data-table-pattern/spec.md`  
**Design Intelligence**: `design-system/pios-smart-city/MASTER.md`  
**Status**: Ready

## 1. Technical Architecture & Component
- Component: `resources/views/components/soft-ui/datatable.blade.php`.
- Container: `.card.shadow-blur` with `.table-responsive`.
- Contrast & Sizing: `text-xxs font-weight-bolder opacity-7` for headers; `text-xs font-weight-bold` for cell keys.

## 2. Verification Strategy
- PHPUnit component test `SoftUiComponentsTest.php`.
- Playwright E2E verification of table rendering and mobile horizontal overflow protection.

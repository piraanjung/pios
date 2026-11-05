# Feature Specification: 009 Authentication and Official Print

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md`, `pages/auth.md`, `pages/print.md`

## 1. User Scenarios & Testing

### User Story 1 - Accessible Authentication (Priority: P1)
As an officer logging into the backoffice, I want clear accessible input fields with label associations, error descriptions, and CSRF protection, so that login is safe and friction-free.

**Acceptance Scenarios:**
1. **Given** login screen (`/login`), **When** viewed, **Then** inputs have visible focus states, labels match inputs, and Kanit font is used.

### User Story 2 - Standardized Official Print (Priority: P2)
As a cashier printing a bill receipt, I want A4 official layout and 80mm thermal receipt formats that render cleanly with proper page breaks and legible print typography.

**Acceptance Scenarios:**
1. **Given** print action, **When** print stylesheet triggers, **Then** sidebars and non-printable elements are hidden via `@media print`.

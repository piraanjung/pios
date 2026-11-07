# Feature Specification: 006 Mobile Field Staff Meter Reading

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md` & `pages/mobile-liff.md`

## 1. User Scenarios & Testing

### User Story 1 - One-Handed Field Reading (Priority: P1)
As a water meter reader in the field under bright sunlight, I want high-contrast input controls with touch targets >= 48px in the bottom thumb zone, so that I can record meter values quickly with one hand without mis-tapping.

**Acceptance Scenarios:**
1. **Given** mobile viewport (375-390px), **When** viewing the meter reading screen, **Then** all primary action buttons have height >= 48px and touch spacing >= 8px.
2. **Given** numeric input, **When** focused, **Then** large number keypad triggers without page zooming.

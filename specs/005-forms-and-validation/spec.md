# Feature Specification: 005 Forms and Validation (Citizen Onboarding Wizard)

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md` & `pages/dashboard.md`

## 1. User Scenarios & Testing

### User Story 1 - Multi-Step Citizen Onboarding (Priority: P1)
As an administrative officer, I want to onboard a new citizen and attach utilities (Tabwater meter, Recycle bank, Welfare fund) via a clear multi-step wizard with real-time validation error summaries, so that citizen profiles are created accurately on the first attempt without confusing errors.

**Independent Test:** Submit invalid form data on `/features/onboard-citizen`; verify error summary appears with clear Thai messages, and valid submission creates citizen and redirects with success toast.

**Acceptance Scenarios:**
1. **Given** missing required fields, **When** submitting, **Then** error summary displays at the top of the form with red alert styling.
2. **Given** valid citizen data and service flags, **When** submitting, **Then** citizen is saved, service ledger entries are generated, and a success alert confirms completion.

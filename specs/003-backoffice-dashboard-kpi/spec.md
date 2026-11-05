# Feature Specification: 003 Backoffice Dashboard KPI & Charts

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md` & `pages/dashboard.md`

## 1. User Scenarios & Testing

### User Story 1 - Municipal KPI Statistics (Priority: P1)
As a municipal executive, I want to view critical KPIs (community fund balance, recycle members, waste volume, AI kiosk availability) within 5 seconds of opening the dashboard so that I can monitor municipal performance in real time.

**Independent Test:** Load `/preview/soft-ui-pro`; all 4 stat cards render with Kanit typography (`700` weight on values, `600` on titles), proper icon gradient shapes, and growth percentages.

**Acceptance Scenarios:**
1. **Given** any stat card, **When** rendered, **Then** value has `.font-weight-bolder` with clean currency/unit formatting.
2. **Given** positive growth, **When** rendered, **Then** green percentage badge (`.text-success`) displays alongside comparison metadata.

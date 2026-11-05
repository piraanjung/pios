# Feature Specification: 002 Backoffice Shell & Navigation

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md` & `pages/dashboard.md`

## 1. User Scenarios & Testing

### User Story 1 - Tenant Context & Official Identity (Priority: P1)
As a municipal officer managing municipal utilities, I want to clearly see which Organization (อปท.) I am currently operating under, including its official logo and gradient badge, so that I never execute actions in the wrong tenant context.

**Independent Test:** Access any backoffice dashboard page; verify the tenant badge shows the active organization name with the hybrid gradient authority badge.

**Acceptance Scenarios:**
1. **Given** an officer logged in to "เทศบาลตำบลบ้านด่าน", **When** viewing the top navigation and sidebar, **Then** `.tenant-badge` renders "เทศบาลตำบลบ้านด่าน" with `--gradient-primary`.
2. **Given** any screen resolution, **When** scrolling down the dashboard, **Then** the Navbar applies smooth Glassmorphism blur (`blur shadow-blur`).

---

### User Story 2 - Accessible Sidebar Navigation (Priority: P2)
As a keyboard or screen-reader user, I want the sidebar items to have explicit focus indicators (`:focus-visible`), aria roles, and distinct active indicators so that navigation is fully accessible.

**Acceptance Scenarios:**
1. **Given** keyboard navigation (Tab/Shift+Tab), **When** focusing sidebar links, **Then** a visible focus outline (`outline: 2px solid var(--color-accent)`) appears.
2. **Given** the active module route, **When** rendered, **Then** the active link has `bg-gradient-primary` or module accent with high contrast white text.

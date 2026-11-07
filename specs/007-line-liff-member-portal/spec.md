# Feature Specification: 007 LINE LIFF Member Portal

**Feature Branch**: `feat/ux-redesign-speckit`  
**Created**: 2026-11-05  
**Status**: Ready for Implementation  
**Design System Ref**: `design-system/pios-smart-city/MASTER.md` & `pages/mobile-liff.md`

## 1. User Scenarios & Testing

### User Story 1 - Citizen Self-Service in LINE (Priority: P1)
As a municipal resident opening the LINE Official Account, I want to view my current water bill, recycle deposit points, and community welfare balance with modern cards and Kanit typography, so that I can monitor my municipal services conveniently on my smartphone.

**Acceptance Scenarios:**
1. **Given** citizen authenticated via LIFF, **When** loading the portal, **Then** my points balance, unpaid bills, and welfare status display clearly.
2. **Given** any bill, **When** tapping "ชำระเงิน", **Then** standard promptpay QR code is generated.

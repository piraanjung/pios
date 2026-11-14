# KeptKaya Module Bug Fixes, Financial Integrity & UX/UI Overhaul Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Fix critical HTTP 500 bugs, enforce financial integrity (multi-session/multi-tab cart isolation, offline resilience, idempotency, zero-negative-balance), resolve Linux case-sensitivity issues, overhaul UX/UI for non-tech-savvy government officers, and verify end-to-end with Playwright.

**Architecture:** Vertical Slice & Laravel Blade architecture. Add idempotency tokens and DB locking (`lockForUpdate()`) to purchase, sell, and withdraw transactions. Enforce synchronized balance between `KpRecycleBankMember` and `KPBankAccount`. Build Gov-friendly guided UX in Soft UI Dashboard with responsive desktop/mobile action bars.

**Tech Stack:** PHP 8.2+ / Laravel 11 / MySQL / Blade / JavaScript (Vanilla + jQuery) / Playwright E2E.

**Spec:** `docs/superpowers/specs/2026-11-14-keptkayas-architecture-and-ux-spec.md` (and artifact `keptkayas-architecture-and-ux-plan.md`).

---

### Task 1: Fix Bug #1 (`recycle-bank/members/create` HTTP 500) & Bug #4 (`history` missing user filtering)

**Files:**
- Modify: `app/Http/Controllers/KeptKaya/RecycleBankController.php`
- Modify: `resources/views/keptkayas/recycle_bank/history.blade.php`
- Test: `tests/Feature/KeptKayaIntegrityTest.php`

- [ ] **Step 1: Write failing test in `tests/Feature/KeptKayaIntegrityTest.php` for `members/create` and `history/{userId}`**
- [ ] **Step 2: Run test to verify RED**
- [ ] **Step 3: Fix `RecycleBankController.php` (`is_waste_bank` column name, strict required `$userId`, where clause)**
- [ ] **Step 4: Run test to verify GREEN**
- [ ] **Step 5: Commit Task 1**

---

### Task 2: Fix Bug #2 (`sell/form` & `store` HTTP 500, Strict Null Checks & Org Bank Account Guard)

**Files:**
- Modify: `app/Http/Controllers/KeptKaya/KpSellController.php`
- Modify: `resources/views/keptkayas/sell/sell_form.blade.php`
- Test: `tests/Feature/KeptKayaIntegrityTest.php`

- [ ] **Step 1: Write failing test for `sell/form` null staff/user and missing org bank account**
- [ ] **Step 2: Run test to verify RED**
- [ ] **Step 3: Update `KpSellController.php` and `sell_form.blade.php` with popup modal, disabled fieldset when unconfigured, and KPBankAccount model reference**
- [ ] **Step 4: Run test to verify GREEN**
- [ ] **Step 5: Commit Task 2**

---

### Task 3: Enforce Financial Integrity & Multi-Tab / Network Drop Protection (Bug #3)

**Files:**
- Modify: `app/Http/Controllers/KeptKaya/KpPurchaseController.php`
- Modify: `app/Http/Controllers/KeptKaya/WithdrawController.php`
- Modify: `resources/views/keptkayas/purchase/purchase_form.blade.php`
- Modify: `resources/views/keptkayas/purchase/cart.blade.php`
- Modify: `resources/views/keptkayas/withdraw_form.blade.php`
- Test: `tests/Feature/KeptKayaIntegrityTest.php`

- [ ] **Step 1: Write failing tests for per-member cart isolation, double-submit idempotency, and zero-negative-balance lock**
- [ ] **Step 2: Run test to verify RED**
- [ ] **Step 3: Implement per-user cart session (`purchase_cart.{user_id}`), transaction idempotency tokens, held withdrawal subtraction, and DB lockForUpdate()**
- [ ] **Step 4: Run test to verify GREEN**
- [ ] **Step 5: Commit Task 3**

---

### Task 4: Fix Case-Sensitivity & Undefined Routes (Bug #5)

**Files:**
- Modify: `resources/views/layouts/keptkaya_navigation_annual.blade.php`
- Modify: `app/Http/Controllers/KeptKaya/CartController.php`
- Modify: `resources/views/keptkayas/kp_usergroup/edit.blade.php`
- Modify: `resources/views/keptkayas/wbin_payrate_per_months/edit.blade.php`
- Modify: `resources/views/keptkayas/w/waste_bins/edit.blade.php`
- Modify: `resources/views/keptkayas/w/users/edit.blade.php`
- Test: `tests/Feature/KeptKayaCaseSensitivityTest.php`

- [ ] **Step 1: Write automated Linux case-sensitivity and route existence test in `tests/Feature/KeptKayaCaseSensitivityTest.php`**
- [ ] **Step 2: Run test to verify RED**
- [ ] **Step 3: Fix model imports, namespaces, view directives, and route names**
- [ ] **Step 4: Run test to verify GREEN**
- [ ] **Step 5: Commit Task 4**

---

### Task 5: UX/UI Overhaul for Government Officers (Desktop & Mobile)

**Files:**
- Modify: `resources/views/layouts/keptkaya.blade.php`
- Modify: `resources/views/layouts/keptkaya_navigation.blade.php`
- Modify: `resources/views/keptkayas/purchase/select_route.blade.php`
- Modify: `resources/views/keptkayas/purchase/select_user.blade.php`
- Modify: `resources/views/keptkayas/purchase/purchase_form.blade.php`
- Modify: `resources/views/keptkayas/purchase/cart.blade.php`
- Modify: `resources/views/keptkayas/withdraw_form.blade.php`

- [ ] **Step 1: Enhance layout and navigation (Gov-friendly typography, 48px touch targets, guided step stepper 1-4, mobile hamburger toggle)**
- [ ] **Step 2: Add offline banner and single-flight submit locks to purchase and withdraw forms**
- [ ] **Step 3: Run PHPUnit regression suite**
- [ ] **Step 4: Commit Task 5**

---

### Task 6: Playwright E2E Verification Across Desktop & Mobile Viewports

**Files:**
- Create: `tests/e2e/keptkayas.spec.js`

- [ ] **Step 1: Create Playwright test suite for Desktop (1280x800) and Mobile (390x844)**
- [ ] **Step 2: Execute Playwright test suite and capture screenshots**
- [ ] **Step 3: Verify all test cases pass without errors**
- [ ] **Step 4: Commit and push via `finishing-a-development-branch`**

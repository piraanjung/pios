# PIOS Vertical Slice Architecture (VSA), Laravel 12 Migration & Soft UI PRO Refactoring Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** ปรับโครงสร้างโปรเจกต์ `qa_envsogo_pios` สู่สถาปัตยกรรม Vertical Slice Architecture (VSA), อัปเกรดสู่ Laravel เวอร์ชันล่าสุด (Laravel 12 / PHP 8.3+), ปิดช่องโหว่ความปลอดภัยและบั๊ก PHP 8.2+ ทั้ง 26 จุด, ทำความสะอาด Git Repository, และวางระบบ Master Layout & Component ตามมาตรฐาน Soft UI Dashboard PRO Laravel

**Architecture:** ใช้สถาปัตยกรรมแบบ Vertical Slice (`app/Features/{BoundedContext}/{SliceName}`) คู่กับ Shared Kernel (`app/Shared/`) โดยใช้ `VerticalSliceServiceProvider` โหลด Routes และ Views ของแต่ละ Slice อัตโนมัติ ยุบรวมตารางบัญชีธนาคารขยะสู่ `kp_bank_accounts` และยุบรวม Layout ทั้ง 29 ไฟล์สู่ 4 Master Layouts มาตรฐานของ Soft UI Dashboard PRO Laravel

**Tech Stack:** PHP 8.3+/8.5, Laravel 12, Spatie Laravel Permission v6, Laravel Sanctum v4, Vite, Bootstrap 5 (Soft UI Dashboard PRO Design System), PHPUnit 11, Larastan v3

**Spec:** `docs/architecture/vsa-and-laravel-upgrade-master-plan.md`, `docs/ui-ux/soft-ui-dashboard-pro-design-system.md`, `docs/adr/0001-vertical-slice-architecture-adoption.md` ถึง `0006-git-hygiene-and-gitignore-policy.md`

## Global Constraints

- ห้าม Commit ไฟล์ `.env`, `.env.zip`, `vendor/`, `node_modules/`, `.DS_Store`, `repomix-output.xml` หรือไฟล์ที่มีคำว่า `copy` เข้าสู่ Git เด็ดขาด (ADR-0006)
- ห้ามเขียน `Auth::login($user)` โดยรับค่า ID จาก Request URL/Body โดยไม่ผ่านการตรวจสอบ Cryptographic Token เด็ดขาด (ADR-0003)
- ทุก Eloquent Model ที่มีคอลัมน์ `org_id_fk` ต้องใช้ Trait `BelongsToOrganization` เสมอ (ADR-0003)
- ฟีเจอร์ใน `app/Features/{Domain}/{Slice}/` ต้องใช้ Single-Action Invokable Controller (`__invoke`) คู่กับ `{Slice}Action` และ `{Slice}DTO` เสมอ (ADR-0001)
- ห้ามสร้างไฟล์ Layout แยกตามโมดูลเพิ่ม ให้ใช้เฉพาะ 4 Master Layouts (`layouts.app`, `layouts.guest`, `layouts.mobile`, `layouts.print`) และ `<x-soft-ui.*>` Blade Components เท่านั้น (ADR-0005)
- ใน Spatie Permission v6 ต้องใช้ Namespace `Spatie\Permission\Middleware\*` (เอกพจน์) เสมอ (ADR-0002)

## Review Focus

1. **IDOR Account Takeover บน LINE LIFF (`/line/dashboard/{pref_id}/{org_id}`):** เมื่อผู้ใช้ที่ไม่ได้ล็อกอินหรือไม่ได้ถือครอง `pref_id` นั้นเปิด URL ดังกล่าว ระบบต้องปฏิเสธการเข้าถึงหรือบังคับตรวจสอบสิทธิ์ ไม่สั่ง `Auth::login()` ให้ผู้โจมตีเด็ดขาด
2. **PHP 8.2+ Variable-Variable & Null Property Dereference:** การผูกมิเตอร์ประปาใน `UserController::attachMeter` และการสมัครสมาชิกผ่าน `LineLiffController::user_line_register` ต้องไม่เกิด Error `Undefined variable $1` หรือ `Attempt to read property "id" on null`
3. **Cross-Tenant Data Isolation (`org_id_fk`):** เมื่อแอดมินของ อปท. A (`org_id_fk = 1`) เรียกดูรายชื่อสมาชิกขยะ/สวัสดิการ หรือสั่งลบสมาชิกของ อปท. B (`org_id_fk = 2`) ระบบต้องไม่แสดงข้อมูลข้าม อปท. และต้องคืนค่า `404 Not Found` หรือ `403 Forbidden`
4. **Welfare Auto-Deduction & Eligibility SQL Schema Alignment:** การตรวจสอบสิทธิ์สวัสดิการต้องรวมน้ำหนักจาก `kp_purchase_transactions.total_weight` (ไม่ใช่ `weight`) และการหักเงินฌาปนกิจต้องหักจากบัญชีธนาคารขยะ (`kp_bank_accounts`) พร้อมบันทึก Ledger โดยไม่อ้างอิงคอลัมน์ `users.wallet_balance` ที่ไม่มีอยู่จริง
5. **Kiosk Dynamic Citizen Identification:** การส่งธุรกรรมขวดจากตู้ Kiosk (`submitTransaction` / `storeTransaction`) ต้องผูกกับ `user_id` และ `kp_user_w_pref_id_fk` ของผู้ใช้งานจริงที่ระบุใน Request/Session ห้ามฮาร์ดโค้ดเป็น `10` หรือ `13`

---

### Task 1: Git Hygiene, `.gitignore` Standardization & Backup File Cleanup (ADR-0006)

**Files:**
- Modify: `.gitignore`
- Delete (Untrack from Git Index only, keep local): `node_modules/`, `vendor/`, `.env`, `.env.zip`, `_ide_helper.php`, `repomix-output.xml`, `.ftpquota`, `my_file.txt`, `**/.DS_Store`
- Delete (Remove duplicate backup files): `resources/views/kiosk copy/`, `app/Http/Controllers/_Backup_Unused/`, `resources/views/**/* copy*.php`, `public/js/accessmenu copy2.js`, `"public/`

**Interfaces:**
- Consumes: none
- Produces: Clean Git Index without secrets, dependencies, or duplicate `* copy*.php` files

- [ ] **Step 1: Update `.gitignore` with comprehensive Laravel 12 + Security + macOS rules**
- [ ] **Step 2: Untrack `vendor/`, `node_modules/`, `.env`, `.env.zip`, `.DS_Store`, `repomix-output.xml`, `_ide_helper.php` from Git index (`git rm --cached`) while preserving local `.env`, `vendor/`, and `node_modules/`**
- [ ] **Step 3: Remove duplicate `* copy*.php`, `kiosk copy/`, `_Backup_Unused/`, and malformed `"public/` paths from repository**
- [ ] **Step 4: Verify `git status --ignored` shows `.env`, `vendor/`, and `node_modules/` as ignored and untracked**

---

### Task 2: Emergency Security & PHP 8.2+ Runtime Hotfixes (ADR-0003 & ADR-0004)

**Files:**
- Modify: `app/Http/Controllers/LineLiffController.php`
- Modify: `app/Http/Controllers/Admin/UserController.php`
- Modify: `app/Http/Controllers/Admin/WelfareController.php`
- Modify: `app/Http/Controllers/Admin/UserWelfareController.php`
- Modify: `app/Http/Controllers/Admin/WelfareMemberController.php`
- Modify: `app/Http/Controllers/Admin/ExcelController.php`
- Modify: `app/Imports/UserImport.php`
- Modify: `app/Models/User.php`
- Modify: `app/Models/FoodWaste/FoodWasteUserPreference.php`
- Modify: `app/Http/Controllers/KeptKaya/UserWasteController.php`
- Modify: `app/Http/Controllers/KeptKaya/RecycleBankController.php`
- Modify: `app/Http/Controllers/FoodWaste/AdminMemberWasteController.php`
- Modify: `app/Http/Controllers/FoodWaste/UserFoodWasteController.php`
- Modify: `app/Http/Controllers/Api/KioskController.php`
- Modify: `app/Http/Controllers/Kiosk/KioskController.php`
- Modify: `routes/admin.php`, `routes/keptkaya_route.php`, `routes/web.php`
- Test: `tests/Feature/SecurityAndRuntimeHotfixesTest.php`

**Interfaces:**
- Consumes: `App\Models\User`, `App\Services\UserService`
- Produces:
  - `User::wastePreference(): HasOne` (alias to `kpUserPreference()`)
  - Safe `LineLiffController::dashboard` without unverified `Auth::login()`
  - Fixed `UserController::attachMeter` without `$$user->id`
  - Fixed `WelfareController::processAutoDeductionAndNotify` deducting from `KpBankAccount` / `KpRecycleBankMember` instead of non-existent `users.wallet_balance`

- [ ] **Step 1: Fix `User.php` (`wastePreference()` relation alias) and `FoodWasteUserPreference.php` (`$casts = ['user_id' => 'integer']`)**
- [ ] **Step 2: Fix `LineLiffController.php` (remove unverified `Auth::login($user)` IDOR in `dashboard`, and fix `$user->org->id` -> `$user->org_id_fk` in `user_line_register`)**
- [ ] **Step 3: Fix `Admin\UserController.php` (`$$user->id` -> `$user->id` in `attachMeter`, assign `'Tabwater User'` role, fix `'organization_id'` -> `'org_id_fk'` in `register`, and remove dangerous `truncate()` calls in `_index`)**
- [ ] **Step 4: Fix `UserWelfareController.php` (`sum('weight')` -> `sum('total_weight')`), `WelfareController.php` (replace `$member->decrement('wallet_balance', $fee)` with `KpBankAccount`/`KpRecycleBankMember` balance deduction), and `WelfareMemberController.php` (enforce `org_id_fk` on `destroy`)**
- [ ] **Step 5: Fix `ExcelController.php` (`UsersImport` -> `UserImport`) and `UserImport.php` (sync via `KpBankAccount` & `KpUserWastePreference` with dynamic `org_id_fk`)**
- [ ] **Step 6: Fix Kiosk hardcoded user IDs in `Api\KioskController.php` and `Kiosk\KioskController.php`, and enforce `org_id_fk` scoping in `UserWasteController`, `RecycleBankController`, and `AdminMemberWasteController`**
- [ ] **Step 7: Fix broken resource routes in `routes/admin.php`, `routes/keptkaya_route.php`, and `routes/web.php`**
- [ ] **Step 8: Run PHP syntax & unit/feature verification on all modified files**

---

### Task 3: VSA Foundation, Shared Kernel & Unified Onboarding Slice (ADR-0001 & ADR-0004)

**Files:**
- Create: `app/Shared/Tenancy/TenantContext.php`
- Create: `app/Shared/Tenancy/BelongsToOrganization.php` (enhanced with `TenantContext`)
- Create: `app/Shared/Providers/VerticalSliceServiceProvider.php`
- Create: `app/Features/IdentityAndOnboarding/OnboardCitizen/OnboardCitizenDTO.php`
- Create: `app/Features/IdentityAndOnboarding/OnboardCitizen/OnboardCitizenRequest.php`
- Create: `app/Features/IdentityAndOnboarding/OnboardCitizen/OnboardCitizenAction.php`
- Create: `app/Features/IdentityAndOnboarding/OnboardCitizen/OnboardCitizenController.php`
- Create: `app/Features/IdentityAndOnboarding/OnboardCitizen/routes.php`
- Create: `app/Features/Welfare/CheckMemberEligibility/CheckMemberEligibilityAction.php`
- Create: `app/Features/Kiosk/SubmitKioskDeposit/SubmitKioskDepositAction.php`
- Create: `app/Features/LineLiff/VerifyLineSession/VerifyLineSessionAction.php`
- Create: `database/migrations/2026_11_05_000001_consolidate_recycle_bank_and_welfare_ledgers.php`
- Modify: `config/app.php` (register `VerticalSliceServiceProvider`)
- Test: `tests/Unit/Features/IdentityAndOnboarding/OnboardCitizenDTOTest.php`

**Interfaces:**
- Consumes: `App\Services\UserService`, `App\Models\User`
- Produces:
  - `App\Shared\Tenancy\TenantContext::currentOrgId(): ?int`
  - `App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenAction::execute(OnboardCitizenDTO $dto): User`
  - `App\Shared\Providers\VerticalSliceServiceProvider`

- [ ] **Step 1: Implement `App\Shared\Tenancy\TenantContext` and `App\Shared\Providers\VerticalSliceServiceProvider`**
- [ ] **Step 2: Create migration `2026_11_05_000001_consolidate_recycle_bank_and_welfare_ledgers.php` to ensure `kp_bank_accounts` has `member_number`, `balance`, `points` and create `welfare_fund_ledgers` table**
- [ ] **Step 3: Implement `OnboardCitizenDTO`, `OnboardCitizenRequest`, `OnboardCitizenAction`, `OnboardCitizenController`, and `routes.php` under `app/Features/IdentityAndOnboarding/OnboardCitizen/`**
- [ ] **Step 4: Implement vertical slice actions for `Welfare\CheckMemberEligibility`, `Kiosk\SubmitKioskDeposit`, and `LineLiff\VerifyLineSession`**
- [ ] **Step 5: Register `VerticalSliceServiceProvider` in `config/app.php` and delegate `UserService` to the unified logic**

---

### Task 4: Soft UI Dashboard PRO Master Layouts & Blade Component Library (ADR-0005)

**Files:**
- Create: `resources/views/layouts/soft-ui-pro.blade.php` (Unified Master Backoffice Layout)
- Create: `resources/views/components/soft-ui/card.blade.php`
- Create: `resources/views/components/soft-ui/stat-card.blade.php`
- Create: `resources/views/components/soft-ui/button.blade.php`
- Create: `resources/views/components/soft-ui/badge.blade.php`
- Create: `resources/views/components/soft-ui/alert.blade.php`
- Create: `resources/views/components/soft-ui/modal.blade.php`
- Create: `resources/views/components/soft-ui/datatable.blade.php`
- Modify: `resources/views/layouts/partials/flash.blade.php`
- Modify: `resources/views/layouts/admin1.blade.php`, `resources/views/layouts/keptkaya.blade.php`, `resources/views/layouts/super-admin.blade.php` (enable flash messages & align with Soft UI PRO tokens)

**Interfaces:**
- Consumes: `public/soft-ui/assets/*`, `App\Support\PageTitle`
- Produces: Reusable `<x-soft-ui.card>`, `<x-soft-ui.stat-card>`, `<x-soft-ui.button>`, `<x-soft-ui.badge>`, `<x-soft-ui.alert>`, `<x-soft-ui.modal>`, `<x-soft-ui.datatable>` and `layouts.soft-ui-pro`

- [ ] **Step 1: Create `resources/views/components/soft-ui/*` Blade components following Soft UI Dashboard PRO Laravel specifications**
- [ ] **Step 2: Create `resources/views/layouts/soft-ui-pro.blade.php` master layout with dynamic module accent colors, organization switcher, breadcrumb resolver, and SweetAlert2 / Bootstrap 5 Toast flash notifications**
- [ ] **Step 3: Re-enable `@include('layouts.partials.flash')` across existing layouts so flash messages are never swallowed**

---

### Task 5: Upgrade Project Configuration to Latest Laravel (Laravel 12 / PHP 8.3+ / Vite / Spatie v6) (ADR-0002)

**Files:**
- Modify: `composer.json`
- Modify: `package.json`
- Create: `vite.config.js`
- Delete: `webpack.mix.js`
- Modify: `app/Http/Kernel.php` (support both Spatie v5/v6 middleware resolution seamlessly or update to modern middleware aliases)
- Modify: `Dockerfile`
- Modify: `phpunit.xml`

**Interfaces:**
- Consumes: Composer & NPM configuration
- Produces: Laravel 12 + Vite + PHP 8.3+ ready configuration

- [ ] **Step 1: Update `composer.json` to target Laravel 12 (`^12.0`), Sanctum `^4.0`, Spatie Permission `^6.10`, PHPUnit `^11.0`, Collision `^8.1`, Larastan `^3.0` while keeping backward-compatible scripts**
- [ ] **Step 2: Replace `webpack.mix.js` with `vite.config.js` and clean `package.json` (remove `react-native-inappbrowser-reborn` and `laravel-mix`)**
- [ ] **Step 3: Update `Dockerfile` to `php:8.3-apache` and `app/Http/Kernel.php` middleware aliases**
- [ ] **Step 4: Run PHP verification & test suite using `/opt/homebrew/Cellar/php/8.5.10/bin/php`**

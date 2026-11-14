# UI Redesign & Error Fixes Implementation Plan

> **Goal:** Fix PHP 8.5 deprecation output in DOM, fix Keptkayas mobile sidebar styling & backdrop, redesign `/accessmenu` (both Card Mode & Wheel Mode), and overhaul the Tabwater Staff Mobile meter reading screen.

---

### Task 1: Eliminate PHP 8.5 Deprecation Output at Bootstrap
- **Target Files:**
  - `public/index.php`
  - `artisan`
  - `config/database.php`
- **Actions:**
  - In `public/index.php` and `artisan`, silence deprecated notices early: `error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);` before Laravel loads configuration.
  - In `config/database.php`, ensure `PDO::MYSQL_ATTR_SSL_CA` constant is safely guarded or conditionally mapped.
- **Verification:** Run `curl -s http://127.0.0.1:8000/login | head -n 10` ensuring no `Deprecated:` output precedes `<!DOCTYPE html>`.

---

### Task 2: Fix Keptkayas Sidebar and Mobile Navigation
- **Target Files:**
  - `resources/views/layouts/keptkaya_mobile_navigation.blade.php`
  - `resources/views/layouts/keptkaya_mobile.blade.php`
  - `resources/views/layouts/keptkaya.blade.php`
- **Actions:**
  - Change all `<li class="nav-item1">` to `<li class="nav-item">`.
  - Fix icon contrast: replace white-on-white SVG icons with visible FontAwesome 6 icons.
  - In `keptkaya_mobile.blade.php`, add a dark backdrop overlay (`#sidenav-backdrop`) when `#sidenav-main` is opened on mobile so background text does not bleed through.
  - Remove duplicate jQuery loading (`jquery-1.12.4.js`).
- **Verification:** View mobile sidebar in browser/Playwright and check that sidebar is solid white with dark overlay and visible icons.

---

### Task 3: Redesign `/accessmenu` (Card Mode & Wheel Mode)
- **Target File:**
  - `resources/views/accessmenu.blade.php`
- **Actions:**
  - Replace broken Kit FontAwesome script with reliable FontAwesome 6 CDN (`@include('layouts.partials.head-fonts')` or CDN).
  - Clean up top navigation bar: replace `#otp-connect` with structured top navbar.
  - Redesign Wheel Mode: 2-column responsive layout (Left: Organization info banner; Right: 420x420px balanced circular menu) instead of `body { display: flex; justify-content: flex-end; }` with floating overlapping text.
  - Redesign Card Mode: 8 clear government module cards with bold Kanit typography, numbered badges, high-contrast action buttons, and make it default or seamlessly toggleable.
- **Verification:** Capture desktop screenshots for both Card Mode and Wheel Mode, verify no text overlap and icons rendered.

---

### Task 4: Overhaul Tabwater Staff Mobile Layout and Meter Reading Screen
- **Target Files:**
  - `resources/views/layouts/tabwater_staff_mobile.blade.php`
  - `resources/views/tabwater/staff/mobile/meter_reading.blade.php`
  - `app/Http/Controllers/Tabwater/StaffMobileController.php`
- **Actions:**
  - Update `StaffMobileController@meter_reading` to eager load `$meter->user` and address info.
  - Modernize `tabwater_staff_mobile.blade.php` with `layouts/partials/unified-header.blade.php` and remove outdated 200px pink banner.
  - Redesign `meter_reading.blade.php`:
    - Customer information card (name, meter number, address, zone).
    - Large touch-friendly input for previous reading (read-only) and current reading (`inputmode="numeric"`).
    - Live calculation of water consumption (`current - previous`).
    - Validation preventing current reading < previous reading with instant warning.
    - Large touch button for saving (`บันทึกการจดมิเตอร์`).
- **Verification:** Capture mobile screenshot and verify clean government form with live calculation.

---

### Task 5: End-to-End Verification and Screenshot Generation
- **Target Files:**
  - `scripts/capture-acc-keptkayas-screenshots.js`
  - `docs/screenshots/`
  - `docs/testing/`
- **Actions:**
  - Run all PHPUnit tests (`php artisan test`).
  - Run all Playwright E2E tests (`npx playwright test`).
  - Re-run `node scripts/capture-acc-keptkayas-screenshots.js` to regenerate all 7 screenshots.
  - Verify all 5 user-reported issues are completely resolved.

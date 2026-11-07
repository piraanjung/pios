# รายงานผลการทดสอบ E2E และภาพถ่ายหน้าจอ (E2E Visual Verification & Screenshot Report)

**โปรเจกต์:** `qa_envsogo_pios` (PIOS Smart City Local Government Platform)  
**เครื่องมือทดสอบ:** Playwright (Chromium Engine, Multi-Viewport Emulation)  
**ผลการทดสอบ Playwright:** ผ่าน 15/15 tests (100% Passed ใน 2.4 วินาที)

---

## 1. ผลการทดสอบ E2E อัตโนมัติ (Automated E2E Test Results)

```text
Running 15 tests using 7 workers

  ✓ [desktop-chromium] › 01-citizen-onboarding.spec.ts (293ms)
  ✓ [desktop-chromium] › 02-tabwater-billing-flow.spec.ts (734ms)
  ✓ [desktop-chromium] › 03-kiosk-recycle-deposit.spec.ts (119ms)
  ✓ [desktop-chromium] › 04-line-liff-member-portal.spec.ts (119ms)
  ✓ [desktop-chromium] › 05-soft-ui-visual-verify.spec.ts (680ms)

  ✓ [mobile-liff]      › 01-citizen-onboarding.spec.ts (387ms)
  ✓ [mobile-liff]      › 02-tabwater-billing-flow.spec.ts (691ms)
  ✓ [mobile-liff]      › 03-kiosk-recycle-deposit.spec.ts (42ms)
  ✓ [mobile-liff]      › 04-line-liff-member-portal.spec.ts (50ms)
  ✓ [mobile-liff]      › 05-soft-ui-visual-verify.spec.ts (527ms)

  ✓ [kiosk-touch]      › 01-citizen-onboarding.spec.ts (247ms)
  ✓ [kiosk-touch]      › 02-tabwater-billing-flow.spec.ts (549ms)
  ✓ [kiosk-touch]      › 03-kiosk-recycle-deposit.spec.ts (89ms)
  ✓ [kiosk-touch]      › 04-line-liff-member-portal.spec.ts (68ms)
  ✓ [kiosk-touch]      › 05-soft-ui-visual-verify.spec.ts (764ms)

15 passed (2.4s) — Zero Fatal Console Errors
```

---

## 2. แกลเลอรีภาพถ่ายหน้าจอ E2E (Captured Screenshots Gallery)

ไฟล์ภาพต้นฉบับความละเอียดสูงถูกจัดเก็บอยู่ที่: `docs/screenshots/`

### 1) หน้าจอ Desktop Soft UI PRO Dashboard (1440 x 900)
- **ไฟล์:** `docs/screenshots/01-soft-ui-pro-desktop-dashboard.png`
- **สิ่งที่ตรวจสอบ:**
  - Sidebar Navigation สีขาวโค้งมน พร้อมป้ายชื่อองค์กรแบบ Gradient (`.tenant-badge`)
  - Top Glassmorphism Navbar (`#navbarBlur`) แสดง Breadcrumb และป้ายกำกับหมายเลของค์กร (`Org #1`)
  - การ์ดสถิติ 4 ใบ (`<x-soft-ui.stat-card>`) พร้อม Gradient Shapes และเปอร์เซ็นต์แนวโน้ม
  - แถบแจ้งเตือน Soft UI Alert สีเขียว (`<x-soft-ui.alert color="success">`)
  - ตารางสรุปข้อมูลประชาชน (`<x-soft-ui.datatable>`) พร้อมปุ่ม Action Gradient

### 2) หน้าจอ Mobile Responsive View (390 x 844 — iPhone 14)
- **ไฟล์:** `docs/screenshots/02-soft-ui-pro-mobile-view.png`
- **สิ่งที่ตรวจสอบ:**
  - Sidebar ยุบเป็น Hamburger Menu บนแถบนำทางด้านบนอัตโนมัติ
  - การ์ดสถิติและตารางข้อมูลจัดเรียงแบบ Responsive Stacking ไม่ล้นจอ
  - ตัวหนังสือภาษาไทยฟอนต์ Sarabun อ่านง่ายสบายตา

### 3) หน้าจอด่านเข้าสู่ระบบ (Login Screen)
- **ไฟล์:** `docs/screenshots/03-login-screen.png`
- **สิ่งที่ตรวจสอบ:**
  - ฟอร์มเข้าสู่ระบบความปลอดภัยสูง ป้องกัน CSRF Token (`XSRF-TOKEN`)

### 4) หน้าจอตู้รับซื้อ AIroBacT AI Kiosk (1080 x 1920 — Portrait Touchscreen)
- **ไฟล์:** `docs/screenshots/04-kiosk-login-screen.png`
- **สิ่งที่ตรวจสอบ:**
  - หน้าจอ Fullscreen Portrait สำหรับหน้าจอสัมผัสตู้ Kiosk ริมทาง/อาคารเทศบาล

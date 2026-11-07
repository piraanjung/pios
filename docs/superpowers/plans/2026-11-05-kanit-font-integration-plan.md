# แผนปฏิบัติการปรับใช้ระบบตัวอักษรมาตรฐานด้วยฟอนต์ Kanit (Kanit Font Integration Plan)

> **เอกสารอ้างอิง:** [ADR-0009](../../adr/0009-standardizing-typography-with-kanit-font.md) และ [Soft UI Dashboard PRO Design System](../../ui-ux/soft-ui-dashboard-pro-design-system.md)  
> **เป้าหมาย:** ยุบรวมและเปลี่ยนผ่านระบบฟอนต์ทั่วทั้งโปรเจกต์จาก 6 ฟอนต์ที่กระจัดกระจาย (`Sarabun`, `Prompt`, `Nunito`, `Fredoka One`, `K2D`, `Inter`) ให้เป็นฟอนต์ **Kanit (คณิต)** เพียงหนึ่งเดียว เพื่อให้เข้ากับสไตล์โมเดิร์นของ Soft UI Dashboard PRO และเพิ่มความเร็วในการโหลด

---

## 1. การกำหนดลำดับชั้นตัวอักษร (Typography Scale & Hierarchy with Kanit)

| ระดับความสำคัญ (Level) | ขนาดตัวอักษร (Size) | น้ำหนัก Kanit (Weight) | คลาส CSS / Utility | บริบทการใช้งานในระบบ |
| :--- | :---: | :---: | :--- | :--- |
| **Hero / Stat Numbers** | `24px - 32px` | **700 (Bold)** | `.font-weight-bolder`, `h5 mb-0` | ตัวเลขสรุปยอดเงิน, จำนวนสมาชิก, ปริมาณขยะในการ์ดสถิติ |
| **Page Title (H1/H2)** | `20px - 24px` | **600 (Semi-Bold)** | `h6.font-weight-bolder`, `h4` | หัวข้อหน้าเว็บ, Breadcrumb หัวข้อหลัก, ชื่อองค์กร |
| **Card Header / Modal** | `16px - 18px` | **600 (Semi-Bold)** | `h6.mb-0`, `.modal-title` | หัวข้อกล่องข้อมูล (Card Header), หัวข้อ Modal ยืนยัน |
| **Button / Navigation** | `13px - 14px` | **500 (Medium)** | `.btn`, `.nav-link`, `.tenant-badge` | ปุ่มกด Action ต่างๆ, เมนูนำทาง Sidebar, ป้ายชื่อ อปท. |
| **Table Head / Badges** | `11px - 12px` | **500 (Medium)** | `.text-xxs font-weight-bolder`, `.badge` | หัวตาราง DataTables (`<th>`), ป้ายสถานะสี Gradient |
| **Body / Table Cell** | `13px - 14px` | **400 (Regular)** | `body`, `.table td`, `.form-control` | เนื้อหาตาราง, กล่องกรอกข้อมูล, ข้อความบทความ |
| **Caption / Metadata** | `10px - 12px` | **300 (Light)** | `small`, `.text-xs.text-secondary` | คำอธิบายกำกับใต้ฟิลด์, วันเวลาที่บันทึก, หมายเหตุ |

---

## 2. แผนงาน 5 ขั้นตอน (Implementation Tasks)

---

### Task 1: ตั้งค่า CDN Preload & Local Offline Fallback Strategy

**เป้าหมาย:** เพิ่มความเร็วในการดาวน์โหลดฟอนต์ ลดปัญหา FOUT (Flash of Unstyled Text) และรองรับตู้ AI Kiosk แบบออฟไลน์

**ไฟล์ที่เกี่ยวข้อง:**
- `resources/views/layouts/partials/head-fonts.blade.php` (สร้าง Partial รวมศูนย์)
- `public/fonts/kanit/` (จัดเตรียมไฟล์ WOFF2 แบบ Local สำหรับ Standalone Kiosk)

**ขั้นตอนปฏิบัติ:**
1. สร้างไฟล์ Blade Partial `resources/views/layouts/partials/head-fonts.blade.php`:
   ```html
   <!-- Google Fonts Preconnect & Kanit (ADR-0009) -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
   ```
2. กำหนด `font-display: swap` เสมอ เพื่อให้หน้าจอแสดงผลข้อความทันทีโดยไม่ต้องรอโหลดฟอนต์เสร็จ

---

### Task 2: ผูกฟอนต์เข้ากับ Tailwind CSS และ Global Stylesheet

**เป้าหมาย:** กำหนดให้ Tailwind CSS และ CSS Base Rules ใช้ Kanit เป็น Default Sans-Serif Font

**ไฟล์ที่เกี่ยวข้อง:**
- `tailwind.config.js`
- `resources/css/app.css`

**ขั้นตอนปฏิบัติ:**
1. ปรับปรุง `tailwind.config.js`:
   ```javascript
   const defaultTheme = require('tailwindcss/defaultTheme');

   module.exports = {
       theme: {
           extend: {
               fontFamily: {
                   sans: ['Kanit', 'Open Sans', ...defaultTheme.fontFamily.sans],
               },
           },
       },
   };
   ```
2. ปรับปรุง `resources/css/app.css`:
   ```css
   @layer base {
       :root {
           --font-sans: 'Kanit', 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
       }
       body {
           font-family: var(--font-sans);
           -webkit-font-smoothing: antialiased;
       }
   }
   ```

---

### Task 3: ปรับปรุง Layouts หลักทั้ง 4 แบบ และทำความสะอาดฟอนต์เก่า

**เป้าหมาย:** แทนที่การโหลดฟอนต์ `Sarabun`, `Prompt`, `Nunito` ใน Layouts ทั้งหมดด้วย Kanit

**ไฟล์ที่เกี่ยวข้อง:**
- `resources/views/layouts/soft-ui-pro.blade.php` (Master Layout หลัก)
- `resources/views/layouts/admin1.blade.php`
- `resources/views/layouts/super-admin.blade.php`
- `resources/views/layouts/welfare.blade.php`
- `resources/views/layouts/keptkaya.blade.php`
- `resources/views/layouts/foodwaste.blade.php`
- `resources/views/layouts/tabwater_staff_mobile.blade.php`
- `resources/views/layouts/user_mobile.blade.php`
- `resources/views/lineliff/index.blade.php`
- `resources/views/kiosk/index1.blade.php`

**ขั้นตอนปฏิบัติ:**
1. ใน `resources/views/layouts/soft-ui-pro.blade.php`:
   - แทนที่ลิงก์ฟอนต์ Sarabun ด้วย `@include('layouts.partials.head-fonts')`
   - ปรับ CSS Style ใน `<style>`:
     ```css
     body, .btn, .form-control, .form-select, .dropdown-menu, .table, h1, h2, h3, h4, h5, h6 {
         font-family: 'Kanit', 'Open Sans', sans-serif;
     }
     ```
2. ลบแท็ก `@import url('...family=Prompt')` และ `family=Fredoka+One` ใน `resources/views/lineliff/index.blade.php`
3. ลบการเรียกฟอนต์ `Nunito` และ `Sarabun` ในเลย์เอาต์อื่นๆ ให้เรียกผ่าน Partial เดียวกัน

---

### Task 4: ปรับแต่ง Blade Components ให้สอดรับกับความหนาของ Kanit

**เป้าหมาย:** ปรับตัวเลข `font-weight` ใน Soft UI Blade Components ให้แสดงผลคมชัดตามมาตรฐานสากล

**ไฟล์ที่เกี่ยวข้อง:**
- `resources/views/components/soft-ui/stat-card.blade.php` (ใช้ `font-weight-bolder` / `700`)
- `resources/views/components/soft-ui/card.blade.php` (ใช้ `font-weight-bold` / `600`)
- `resources/views/components/soft-ui/button.blade.php` (ใช้ `font-weight-medium` / `500`)
- `resources/views/components/soft-ui/badge.blade.php` (ใช้ `font-weight-medium` / `500`)
- `resources/views/components/soft-ui/datatable.blade.php` (ใช้ `font-weight-bolder` / `500` สำหรับ Headers)

**ขั้นตอนปฏิบัติ:**
1. ตรวจสอบระยะห่าง (Letter Spacing) ของตัวอักษร Kanit เนื่องจาก Kanit เป็นฟอนต์แบบไม่มีหัว (Modern Geometric) จึงไม่ต้องเพิ่ม `letter-spacing` กว้างเกินไป
2. อัปเดตคู่มือใน `docs/ui-ux/soft-ui-dashboard-pro-design-system.md` ให้บันทึก Kanit Typography System ไว้อย่างเป็นทางการ

---

### Task 5: การทดสอบ Visual Verification และ Regression

**เป้าหมาย:** ยืนยันว่าหน้าจอเรนเดอร์ฟอนต์ Kanit อย่างถูกต้อง ไม่เกิดปัญหาตัวอักษรทับซ้อน (Clipping / Overlapping) และผ่านการทดสอบอัตโนมัติ 100%

**ขั้นตอนปฏิบัติ:**
1. อัปเดตชุดทดสอบ PHPUnit `tests/Feature/Ui/MasterLayoutsVerificationTest.php`:
   - ตรวจสอบว่า `layouts.soft-ui-pro` มีการโหลด `family=Kanit` และประกาศ `font-family: 'Kanit'`
2. รันชุดทดสอบ UI ทั้งหมด:
   ```bash
   /opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit --testsuite Ui
   ```
3. สั่งรันสคริปต์ Playwright จับภาพหน้าจอเปรียบเทียบ (Visual Screenshots):
   ```bash
   node scripts/capture-e2e-screenshots.js
   ```
4. ตรวจสอบภาพถ่ายหน้าจอที่บันทึกใหม่ใน `docs/screenshots/` (Desktop, Mobile LIFF, Login, และ Kiosk)

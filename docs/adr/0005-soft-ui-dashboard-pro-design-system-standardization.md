# ADR 0005: การจัดทำมาตรฐานระบบ UI/UX อ้างอิงตาม Soft UI Dashboard PRO Laravel

## Status
Accepted (วันที่ 2026-11-05)

## Context
ปัจจุบันระบบ `pios` มีปัญหาความซ้ำซ้อนและความไม่สม่ำเสมอของ UI/UX อย่างรุนแรง:
1. **29 Fragmented Layout Files:** ใน `resources/views/layouts/` มีไฟล์ Layout แยกกันถึง 29 ไฟล์ (`admin.blade.php`, `admin1.blade.php`, `admin1_b.blade.php`, `keptkaya.blade.php`, `foodwaste.blade.php`, `annual_trash.blade.php`, `super-admin.blade.php`, `welfare.blade.php` ฯลฯ) ซึ่งก๊อปปี้โค้ด HTML `<head>`, Navbar, Sidenav และ Scripts ซ้ำกันทั้งหมด
2. **Mixed CSS Frameworks & Broken Assets:** มีการผสมกันระหว่าง Soft UI Dashboard Free (v1.0.7), AdminLTE (`public/adminlte`), Tailwind CSS, และ Applight รวมถึงโหลด jQuery/DataTables/Select2 จากทั้ง CDN และ Local ซ้ำซ้อนกัน
3. **Lack of Reusable UI Components:** แต่ละหน้าเขียน HTML ตาราง การ์ด ฟอร์ม และ Modal เองทั้งหมด ทำให้หน้าตาไม่เป็นมาตรฐานเดียวกันและขาดฟีเจอร์ระดับ PRO ของ **Soft UI Dashboard PRO Laravel** (Creative Tim & UPDIVISION)

## Decision
1. **ยุบรวม Layout ทั้ง 29 ไฟล์ ให้เหลือ 4 Master Layouts มาตรฐานตามสถาปัตยกรรม Soft UI Dashboard PRO Laravel:**
   - `layouts/app.blade.php`: Master Layout หลักสำหรับระบบหลังบ้านทุกโมดูล (Admin, Tabwater, KeptKaya, AnnualTrash, FoodWaste, Welfare, Inventory, SmartUtility) รองรับ Dynamic Sidenav ตามโมดูล (`<x-soft-ui.sidenav :module="$currentModule" />`), Glassmorphism Navbar (`navbarBlur`), Breadcrumb อัตโนมัติ, และ Organization Switcher
   - `layouts/guest.blade.php`: Layout สำหรับหน้า Authentication (Login, Register, Forgot Password) สไตล์ Soft UI PRO Cover/Illustration
   - `layouts/mobile.blade.php`: Layout สำหรับ LINE LIFF, เจ้าหน้าที่จดมิเตอร์ประปา (`twmanmobile`), และจุดรับซื้อขยะมือถือ (Mobile-first Soft UI Bottom Nav + Touch Cards)
   - `layouts/print.blade.php`: Layout มาตรฐานสำหรับการพิมพ์เอกสารราชการ A4 / ใบเสร็จความร้อน (ใบแจ้งหนี้ค่าน้ำ, ใบเสร็จรับเงิน, หนังสือแจ้งเตือนตราครุฑ, รายงาน ป.17)
2. **สร้างคลัง Anonymous Blade Components (`resources/views/components/soft-ui/`) ตาม Design System ของ Soft UI Dashboard PRO Laravel:**
   - `<x-soft-ui.card>`, `<x-soft-ui.stat-card>` (Mini statistics card พร้อม Gradient icon shape `bg-gradient-primary/info/success/warning/danger`)
   - `<x-soft-ui.datatable>` (มาตรฐานตารางข้อมูลพร้อม Export Excel/PDF, Search, Filter โซน/ปีงบประมาณ)
   - `<x-soft-ui.form-input>`, `<x-soft-ui.select>` (Choices.js / Select2 Soft UI theme), `<x-soft-ui.wizard>` (Multi-step form สำหรับการลงทะเบียนผู้ใช้และผูกมิเตอร์)
   - `<x-soft-ui.modal>`, `<x-soft-ui.alert>` (พร้อม SweetAlert2 Toast integration สำหรับ Flash messages)
3. **กำหนด Design Tokens และ Typography มาตรฐานสำหรับระบบราชการไทย:**
   - ใช้ Bootstrap 5 + Soft UI Dashboard PRO CSS variables ร่วมกับฟอนต์ `"Open Sans", "Sarabun", sans-serif` เพื่อให้ตัวเลขละตินคมชัดและภาษาไทยอ่านง่ายทั้งบนหน้าจอและงานพิมพ์

## Consequences
- **Positive:** ลดโค้ดซ้ำซ้อนใน `resources/views/layouts/` ลงกว่า 85%, ทุกโมดูลมีรูปลักษณ์ระดับพรีเมียมตาม Soft UI Dashboard PRO Laravel เหมือนกันทั้งระบบ, แก้ไข Header/Navbar/Script ได้จากจุดเดียว, และรองรับ Responsive Mobile/Desktop อย่างสมบูรณ์
- **Negative:** ต้องทยอยปรับ `@extends('layouts.admin1')` / `@extends('layouts.keptkaya')` ใน Views ของแต่ละ Slice ให้ชี้มายัง Master Layout ใหม่ผ่าน View Alias หรือการ Refactor ตาม Slice
- **Constraints (สำหรับ AI Agent):**
  1. ห้ามสร้างไฟล์ Layout ใหม่แยกตามโมดูล (เช่น `layouts/module_x.blade.php`) อีกเด็ดขาด ให้ใช้เฉพาะ 4 Master Layouts (`layouts.app`, `layouts.guest`, `layouts.mobile`, `layouts.print`) เท่านั้น
  2. ห้ามนำคลาสของ AdminLTE (`card-primary`, `btn-block`, `fas` แบบเก่าที่พึ่งพา `public/adminlte`) หรือเขียน Inline `<style>` ซ้ำซ้อนในแต่ละหน้า ให้ใช้ Component `<x-soft-ui.*>` และ Utility classes ของ Soft UI Dashboard PRO (`border-radius-xl`, `bg-gradient-*`, `shadow-blur`, `text-gradient`) เสมอ
  3. ทุกหน้าที่มีการแสดงข้อความแจ้งเตือนผลลัพธ์ (`session('success')`, `session('error')`) ต้องแสดงผลผ่าน Global Flash / SweetAlert2 Component ใน Master Layout เสมอ ห้ามคอมเมนต์ปิดไว้เหมือนใน Layout เดิม

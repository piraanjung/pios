# ADR 0009: การกำหนดมาตรฐานระบบตัวอักษรของโปรเจกต์ด้วยฟอนต์ Kanit (Standardizing Typography with Kanit Font)

## Status
Proposed (วันที่ 2026-11-05)

## Context
ปัจจุบันโปรเจกต์ `qa_envsogo_pios` ประสบปัญหาความไม่เป็นเอกภาพของระบบตัวอักษร (Typography Fragmentation) โดยมีการเรียกใช้ฟอนต์ภาษาไทยแตกต่างกันถึง 6 ฟอนต์ในแต่ละส่วนของระบบ:
1. เลย์เอาต์หลักเดิม (`admin1`, `keptkaya`, `welfare`, `super-admin`): ใช้ `Sarabun` (ฟอนต์ทางการแบบมีหัว) คู่กับ `Open Sans`
2. หน้าจอชำระเงินและตั้งค่างวด (`paymenthistory`, `invoice_period`): ใช้ `@import url('...family=Prompt')`
3. หน้าจอ LINE LIFF และการลงทะเบียนสมาชิก: ผสมกันระหว่าง `Kanit`, `Prompt`, และ `Fredoka One`
4. หน้าจอตู้รับซื้อขยะ AIroBacT Kiosk และระบบพัสดุ (`inventory`): เรียกใช้ `Kanit` และ `K2D`
5. หน้าจอ Breeze / Auth เริ่มต้น: ใช้ `Nunito`

ความกระจัดกระจายนี้ส่งผลให้:
- **ประสบการณ์ผู้ใช้ (UX) ขาดความต่อเนื่อง:** ผู้ใช้งานรู้สึกว่าแต่ละโมดูลเป็นคนละระบบกัน
- **ประสิทธิภาพการโหลดหน้าเว็บลดลง (Web Performance Overhead):** เบราว์เซอร์ต้องดาวน์โหลดไฟล์ฟอนต์จาก Google Fonts หลายตระกูลพร้อมกัน ทำให้เกิดปัญหา Cumulative Layout Shift (CLS) และ Flash of Unstyled Text (FOUT)
- **สไตล์ขัดแย้งกับ Soft UI Dashboard PRO:** สไตล์ของ Soft UI เน้นความทันสมัย ล้ำสมัย (Modern, High-Tech, Glassmorphism, Micro-interactions) ซึ่งฟอนต์ **Kanit (คณิต)** ของ Cadson Demak มีลักษณะไม่มีหัว (Loopless) โครงสร้างเรขาคณิต (Geometric Sans-Serif) ที่เข้ากันได้ดีเยี่ยมกับ Soft UI Dashboard PRO และอ่านง่ายชัดเจนมากบนหน้าจอมือถือ (LINE LIFF) และหน้าจอสัมผัสตู้ Kiosk

## Decision
เราตัดสินใจกำหนดให้ฟอนต์ **Kanit (`https://fonts.google.com/specimen/Kanit`)** เป็นฟอนต์หลักมาตรฐานเพียงหนึ่งเดียวของทั้งโปรเจกต์ (Primary Global Font Family) โดยมีแนวทางปฏิบัติดังนี้:

1. **Font Weights & Glyph Range:**
   - ใช้งาน 5 น้ำหนักหลัก:
     - `300 (Light)`: สำหรับคำอธิบายรอง, หมายเหตุ, ข้อมูล Metadata ขนาดเล็ก (`text-xs text-secondary`)
     - `400 (Regular)`: สำหรับเนื้อหาทั่วไป (Body Text), เซลล์ตาราง DataTables, กล่องข้อความ
     - `500 (Medium)`: สำหรับ Form Labels, หัวข้อเมนูนำทาง (Sidebar Links), ป้ายสถานะ (`.badge`)
     - `600 (Semi-Bold)`: สำหรับหัวข้อฟอร์ม, ปุ่มกด (`.btn`), ป้ายชื่อองค์กร (`.tenant-badge`)
     - `700 (Bold / Bolder)`: สำหรับตัวเลขสถิติสำคัญ (`.stat-card h5`), หัวข้อหน้าเว็บ (`h1`-`h4`), และยอดเงินคงเหลือ
2. **CDN Loading & Performance Strategy:**
   - ใช้ Preconnect URL สำหรับ Google Fonts เพื่อลด Latency:
     ```html
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
     <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     ```
   - เตรียมตัวเลือก Self-Hosted WOFF2 ภายในไดเรกทอรี `public/fonts/kanit/` สำหรับตู้ AI Kiosk ที่อาจต้องทำงานในโหมด Offline / Local Network
3. **CSS & Tailwind Global Integration:**
   - อัปเดต `tailwind.config.js`: กำหนด `fontFamily.sans = ['Kanit', 'Open Sans', 'sans-serif']`
   - อัปเดต `resources/views/layouts/soft-ui-pro.blade.php` และเลย์เอาต์หลักทั้งหมดให้ประกาศ `font-family: 'Kanit', sans-serif;` ครอบคลุมทั้ง `body`, `h1`-`h6`, `.btn`, `.form-control`, `.table`

## Consequences
- **Positive:**
  - อัตลักษณ์ของระบบ (Visual Identity) สอดคล้องกับมาตรฐาน Soft UI Dashboard PRO มีความทันสมัยและดูเป็น Smart City Service Platform มืออาชีพ
  - ประสิทธิภาพการโหลดดีขึ้นอย่างเห็นได้ชัด เนื่องจากทั้งระบบโหลดฟอนต์ชุดเดียว (`Kanit` 5 weights พร้อม `font-display: swap`)
  - อ่านง่าย ชัดเจนบนทุกขนาดหน้าจอ (Desktop Backoffice, Mobile LINE LIFF `390x844`, Kiosk `1080x1920`)
- **Negative:**
  - ต้องปรับแก้ไฟล์ Blade Layouts เก่าและลบ `@import` ฟอนต์เดิม (`Prompt`, `Nunito`, `Sarabun`) ออกจากไฟล์ View ต่างๆ เพื่อป้องกันการโหลดไฟล์ขยะ
- **Constraints (สำหรับ AI Agent):**
  - **ห้าม** เพิ่ม `@import` หรือ `<link>` ดึงฟอนต์อื่นๆ เช่น `Prompt`, `Sarabun`, `Nunito`, `Fredoka One` เข้ามาในไฟล์ View หรือ Component ใหม่อีกต่อไป
  - **บังคับ** ทุก View, Component, หรือ Layout ใหม่จะต้องอ้างอิง `font-family: 'Kanit', sans-serif;` หรือ Tailwind utility `font-sans` เสมอ
  - **บังคับ** ต้องคงคุณสมบัติ `font-display: swap` ไว้ใน URL ของ Google Fonts เสมอ เพื่อป้องกันปัญหาหน้าเว็บค้างขณะรอโหลดฟอนต์

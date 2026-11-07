# ADR 0007: การออกแบบสถาปัตยกรรมการทดสอบระบบแบบครบวงจร (Comprehensive Testing Strategy & QA Pyramid)

## Status
Accepted (วันที่ 2026-11-05)

## Context
โปรเจกต์ `qa_envsogo_pios` มีขอบเขตระบบที่ซับซ้อน ครอบคลุม 7 Bounded Contexts (ระบบประปาหมู่บ้าน Tabwater, ธนาคารขยะรีไซเคิล KeptKaya, ตู้รับซื้อขยะอัตโนมัติ AI Kiosk, ถังขยะเปียกครัวเรือน FoodWaste, ค่าธรรมเนียมขยะรายปี AnnualTrash, กองทุนสวัสดิการชุมชน Welfare, และระบบผู้ดูแลหลายองค์กร Multi-Tenant Admin) โดยในโค้ดเบสเดิมมีปัญหาสำคัญด้านการทดสอบดังนี้:
1. **ไม่มีชุดทดสอบสำหรับ Business Logic และ Multi-Tenancy:** โค้ดเดิมมีเพียงชุดทดสอบเริ่มต้นของ Laravel Breeze (`tests/Feature/Auth/`) ซึ่งล้มเหลวทั้งหมดเมื่อรัน `RefreshDatabase` เนื่องจากไฟล์ Migration เดิมกว่า 140 ไฟล์มีการสร้างตารางซ้ำซ้อน (`staffs`, `organizations`) โดยไม่ตรวจสอบ `Schema::hasTable()`
2. **ความเสี่ยงด้านความถูกต้องของตัวเลขทางการเงิน (Financial & Ledger Integrity):** การคำนวณค่าน้ำประปาแบบขั้นบันได (`PricingType`), แต้มและยอดเงินธนาคารขยะ (`kp_bank_accounts`), และสมุดบัญชีสวัสดิการ (`welfare_fund_ledgers`) ขาด Unit Test และ Integration Test รองรับ
3. **ความเสี่ยงด้านความปลอดภัยข้ามองค์กร (Cross-Tenant Data Leak & IDOR):** ระบบรองรับหลาย อปท. (`org_id_fk`) และมีช่องทางสาธารณะผ่าน LINE LIFF และ IoT Kiosk API ซึ่งต้องมี Automated API & Security Regression Test คอยตรวจสอบทุกครั้งที่มีการแก้ไขโค้ด
4. **การควบคุมคุณภาพ UI ตามมาตรฐาน Soft UI Dashboard PRO:** หลังจากยุบรวม Layout สู่ 4 Master Layouts และสร้าง `<x-soft-ui.*>` Blade Components แล้ว จำเป็นต้องมี UI Verification และ End-to-End (E2E) Test เพื่อป้องกันหน้าจอเพี้ยนหรือ JavaScript/DataTables พัง

## Decision
เราตัดสินใจกำหนดสถาปัตยกรรมการทดสอบ 5 เลเยอร์ (5-Layer Testing Architecture) ให้สอดคล้องกับโครงสร้าง **Vertical Slice Architecture (VSA)** ดังนี้:

1. **Layer 1: Unit Testing (`tests/Unit/`)**
   - ทดสอบหน่วยย่อยที่ไม่พึ่งพาฐานข้อมูล (Zero-DB Dependency) รันเสร็จภายใน `< 100ms`
   - ครอบคลุม DTOs (`OnboardCitizenDTO`), ตัวคำนวณอัตราค่าน้ำประปา/แต้มขยะ, Value Objects, และ State ของ `TenantContext`
2. **Layer 2: Integration Testing (`tests/Integration/` และ `tests/Feature/Slices/`)**
   - ทดสอบการทำงานร่วมกันของ Vertical Slice Actions (`*Action.php`), Database Transactions (`DB::transaction`), Global Scope (`BelongsToOrganization`), และความถูกต้องของตาราง `kp_bank_accounts` / `welfare_fund_ledgers`
   - ใช้ SQLite In-Memory (`:memory:`) ร่วมกับ Schema Blueprint helper สำหรับตารางที่เกี่ยวข้องในแต่ละ Slice เพื่อให้รันได้รวดเร็วและไม่ติดปัญหา Legacy Migrations
3. **Layer 3: API & Contract Testing (`tests/Feature/Api/`)**
   - ทดสอบ RESTful API ทั้งหมดใน `routes/api.php` และ Feature Slice Endpoints
   - เน้นหนักที่ AI Kiosk Workflow (`/api/kiosk/*`), LINE LIFF Verification, Mobile Meter Reading API, โครงสร้าง JSON Schema, HTTP Status Codes (`200`, `201`, `400`, `401`, `403`, `404`, `422`) และการป้องกัน Payload Tampering
4. **Layer 4: UI Verification Testing (`tests/Feature/Ui/` และ Playwright Component Visual Tests)**
   - ทดสอบการเรนเดอร์ Blade Components (`<x-soft-ui.card>`, `<x-soft-ui.stat-card>`, `<x-soft-ui.button>`, `<x-soft-ui.badge>`, `<x-soft-ui.modal>`, `<x-soft-ui.datatable>`) และ Master Layouts ทั้ง 4 แบบ
   - ตรวจสอบ DOM Structure, CSS Classes ของ Soft UI Dashboard PRO, การแสดงผล Flash Alert และการไม่เกิด HTML/Blade syntax ตกค้าง
5. **Layer 5: End-to-End (E2E) Testing (`tests/e2e/` ด้วย Playwright)**
   - ทดสอบ Critical User Journeys ตั้งแต่หน้าบ้านถึงฐานข้อมูลจริงผ่านเบราว์เซอร์ (Chromium / Mobile Viewport สำหรับ LINE LIFF และ Fullscreen Viewport สำหรับ Kiosk)

## Consequences
- **Positive:**
  - ป้องกันบั๊กซ้ำซาก (Regression) ทั้งด้านความปลอดภัย (IDOR, Cross-Tenant leak) และความถูกต้องทางการเงิน
  - นักพัฒนาและ AI Agent สามารถรันชุดทดสอบเฉพาะ Vertical Slice ที่กำลังแก้ไขได้อย่างรวดเร็วในระดับมิลลิวินาที
  - ยืนยันได้ว่า UI ทุกหน้าเป็นไปตามมาตรฐาน Soft UI Dashboard PRO Laravel
- **Negative:**
  - ต้องดูแลรักษา Schema Test Traits สำหรับ SQLite In-Memory จนกว่าจะทำการ Squash ไฟล์ Migration เก่าทั้งหมดในอนาคต
  - การรัน Playwright E2E ใช้เวลาในไปป์ไลน์ CI/CD เพิ่มขึ้นประมาณ 2-4 นาที
- **Constraints (สำหรับ AI Agent):**
  - **ห้าม** เขียนชุดทดสอบที่เชื่อมต่อฐานข้อมูล MySQL ของจริง (Production/Staging) โดยเด็ดขาด ต้องใช้ SQLite `:memory:` หรือฐานข้อมูลทดสอบที่แยกขาดเท่านั้น
  - **บังคับ** ทุกครั้งที่สร้าง Vertical Slice ใหม่ใน `app/Features/{Context}/{Feature}/` จะต้องสร้าง Unit Test สำหรับ DTO และ Feature/Integration Test สำหรับ Action และ Controller คู่กันเสมอ
  - **บังคับ** ทุก API Endpoint หรือ Route ที่รับพารามิเตอร์ `user_id`, `pref_id`, หรือ `org_id` จะต้องมี Test Case ทดสอบกรณีส่งค่าข้ามองค์กร (Cross-Tenant Access) และกรณีไม่ได้ยืนยันตัวตน (`401`/`403`/`422`) เสมอ
  - **บังคับ** เมื่อใช้ PHP CLI บนเครื่องพัฒนาปัจจุบัน ให้รันผ่าน `/opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit` เสมอ

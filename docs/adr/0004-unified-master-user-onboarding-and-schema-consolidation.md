# ADR 0004: การรวมศูนย์สคีมาฐานข้อมูลและไปป์ไลน์การลงทะเบียนผู้ใช้ (Unified Master User Onboarding & Schema Consolidation)

## Status
Accepted (วันที่ 2026-11-05)

## Context
จากการตรวจสอบสถาปัตยกรรมข้อมูลใน `USER_MEMBER_WORKFLOW_AUDIT_TH.md` พบความซ้ำซ้อนและข้อผิดพลาดรุนแรงในฐานข้อมูลและกระบวนการลงทะเบียนสมาชิก:
1. **5 Competing Recycle Bank Schemas:** มีตารางบัญชีธนาคารขยะถึง 5 ตารางที่ทำงานทับซ้อนและไม่ซิงค์กัน (`kp_recycle_bank_members`, `kp_bank_accounts`, `recycle_bank_accounts`, `kp_accounts`, `kp_user_waste_preferences`) ทำให้ผู้ใช้ที่นำเข้าผ่าน Excel (`recycle_bank_accounts`) หรือเปิดสิทธิ์ผ่านหน้าเว็บ (`kp_bank_accounts`) มองไม่เห็นกันในหน้ารับซื้อขยะ (`kp_recycle_bank_members`)
2. **Missing Wallet Column & Welfare Crash:** ระบบสวัสดิการชุมชน (`WelfareController::processAutoDeductionAndNotify`) สั่ง `$member->decrement('wallet_balance', $fee)` บนตาราง `users` ซึ่งไม่มีคอลัมน์ `wallet_balance` อยู่จริง ทำให้เกิด Fatal SQL Error 1054
3. **Fragmented Onboarding & Hardcoded Data:** การสมัครสมาชิกกระจายอยู่ตามคอนโทรลเลอร์ต่างๆ มีการฮาร์ดโค้ด `$userId = 10` ในตู้ Kiosk, ฮาร์ดโค้ด `id_card = '111111111'` ใน `RegisteredUserController`, และไวยากรณ์ผิดพลาด `'meter_id' => $$user->id` ใน `UserController::attachMeter`

## Decision
1. **ยุบรวมตารางบัญชีธนาคารขยะและกระเป๋าเงิน (Schema Consolidation):**
   - ใช้ตาราง `kp_bank_accounts` เป็น **Master Financial & Point Ledger** เพียงตารางเดียวสำหรับธนาคารขยะรีไซเคิล ขยะเปียก และกระเป๋าเงินสวัสดิการ (แยกตาม `entity_type`: `'recycle_bank'`, `'foodwaste_bank'`, `'welfare_wallet'`) พร้อมเพิ่มคอลัมน์ `member_number`
   - สร้าง Data Migration เพื่อโอนย้ายยอดเงิน (`balance`) และแต้มสะสม (`points` / `total_points`) จาก `recycle_bank_accounts`, `kp_accounts`, และ `kp_recycle_bank_members` มารวมไว้ใน `kp_bank_accounts` อย่างสมบูรณ์ แล้วประกาศ `@deprecated` โมเดลเก่า
2. **บังคับใช้ `OnboardCitizenAction` (Unified Master User Onboarding Pipeline):**
   - ทุกช่องทางการนำเข้าหรือสร้างผู้ใช้ (Admin Web UI, LINE LIFF Registration, Kiosk Self-Registration, และ Async Excel Import) ต้องเรียกใช้ `App\Features\IdentityAndOnboarding\OnboardCitizen\OnboardCitizenAction` เพียงจุดเดียวภายใต้ `DB::transaction()`
   - รองรับการผูกบริการทั้ง 5 โมดูล (Tabwater, RecycleBank, AnnualTrash, FoodWaste, Welfare) พร้อมมอบ Spatie Roles (`User`, `Tabwater User`, `Recycle Bank User`, `Annual Trash User`, `Foodwaste User`, `Welfare User`) ให้ครบถ้วนในคราวเดียว
3. **แก้ไขบั๊ก Kiosk และ Welfare Auto-Deduction:**
   - ถอดการฮาร์ดโค้ด `$userId = 10` และ `kp_user_w_pref_id_fk = 13` ใน Kiosk ออกทั้งหมด โดยผูกกับ User Session ที่ยืนยันตัวตนหน้าตู้จริง
   - เปลี่ยนการตัดเงินสวัสดิการฌาปนกิจให้ตัดจาก `KpBankAccount` ของสมาชิกพร้อมบันทึกประวัติลงตาราง `welfare_fund_ledgers`

## Consequences
- **Positive:** ยอดเงินและแต้มสะสมของประชาชนตรงกันทุกช่องทาง (Admin, รถรับซื้อขยะ, ตู้ Kiosk, แอป LINE LIFF, และระบบตัดเงินสวัสดิการ), ไม่มีข้อมูลสูญหายจากการนำเข้า Excel, และแก้ไขบั๊ก Fatal SQL Crash ทั้งหมด
- **Negative:** ต้องรันสคริปต์ Data Migration เพื่อรวมข้อมูลจากตารางเก่าเข้าสู่ `kp_bank_accounts` ด้วยความระมัดระวังก่อนลบโค้ดที่อ้างอิงตารางเก่า
- **Constraints (สำหรับ AI Agent):**
  1. ห้ามเขียนโค้ดอ่านหรือเขียนลงตาราง `recycle_bank_accounts` หรือ `kp_accounts` อีกต่อไป ให้ใช้ `KpBankAccount` (`kp_bank_accounts`) เสมอ
  2. ห้ามสร้างเรคอร์ดในตาราง `users` โดยตรงจากคอนโทรลเลอร์ของโมดูลย่อย ให้เรียกผ่าน `OnboardCitizenAction` หรือ Shared Onboarding Contract เสมอ
  3. ห้ามอ้างอิงคอลัมน์ `users.wallet_balance` หรือ `kp_purchase_transactions.weight` (ชื่อคอลัมน์จริงคือ `total_weight`) เด็ดขาด

# ADR 0001: การปรับโครงสร้างระบบสู่ Vertical Slice Architecture (VSA) และ Shared Kernel

## Status
Accepted (วันที่ 2026-11-05)

## Context
โครงสร้างเดิมของโปรเจกต์ `pios` (`qa_envsogo_pios`) จัดระเบียบแบบ Technical Layered MVC ดั้งเดิม (`app/Http/Controllers/`, `app/Models/`, `resources/views/`, `routes/`) ซึ่งเมื่อระบบขยายตัวครอบคลุมกว่า 10 โมดูลของ อปท. (Core Admin, Tabwater, KeptKaya, AnnualTrash, FoodWaste, Welfare, Inventory, SmartUtility, IoTCompost, Kiosk, LineLiff) ส่งผลให้เกิดปัญหารุนแรงดังนี้:
1. **High Coupling & Low Cohesion:** การแก้ไขฟีเจอร์เดียว (เช่น การลงทะเบียนสมาชิกธนาคารขยะ หรือการออกใบแจ้งหนี้ค่าน้ำประปา) ต้องกระโดดข้ามไฟล์ใน 6–8 โฟลเดอร์ (`app/Http/Controllers/KeptKaya`, `app/Models/KeptKaya`, `app/Imports`, `app/Exports`, `resources/views/keptkayas`, `routes/keptkaya_route.php`)
2. **Fat Controllers & Duplicated Logic:** คอนโทรลเลอร์ขนาดใหญ่ เช่น `Admin\UserController` (828 บรรทัด), `Tabwater\UserMeterInfosController` (614 บรรทัด), `TestController` (56KB) แบกทั้ง Validation, Business Logic, Query และ View rendering จนเกิดโค้ดซ้ำซ้อนและขัดแย้งกันเอง
3. **Namespace & Directory Inconsistency:** ชื่อโฟลเดอร์ไม่ตรงกันและสะกดปะปนกัน เช่น `KeptKaya` vs `Keptkayas` vs `keptkayas`, โมเดลกระจายตัวทั้งใน Root `app/Models/` และโฟลเดอร์ย่อย

## Decision
1. เปลี่ยนสถาปัตยกรรมหลักของระบบสู่ **Vertical Slice Architecture (VSA)** ภายใต้เนมสเปซ `App\Features\{BoundedContext}\{SliceName}` และ `App\Shared\` (Shared Kernel):
   - **`app/Shared/` (Shared Kernel):** เก็บเฉพาะโครงสร้างพื้นฐานที่ใช้ร่วมกันทั้งระบบและปราศจาก Business Logic เฉพาะส่วน ได้แก่ Multi-Tenancy (`SetTenantConnection`, `BelongsToOrganization`), Base Eloquent Models ที่ข้ามโดเมน (`User`, `Organization`, `Zone`, `Subzone`), Helpers, และ Shared UI Components
   - **`app/Features/{Domain}/{Slice}/`:** จัดกลุ่มโค้ดตาม **Use Case / Feature Slice** โดยแบ่ง Bounded Context ออกเป็น 10 โดเมนหลัก:
     1. `IdentityAndOnboarding` (Auth, Master User Onboarding, Role/Permission, Bulk Excel Import)
     2. `Tabwater` (Meter Management, Meter Reading, Invoicing, Payment, OwePaper & Cutmeter, GIS & Production, Reports)
     3. `RecycleBank` (Member Enrollment, Waste Purchase POS, Account Ledger, Shop & Point Redemption)
     4. `AnnualTrash` (Household Bin Subscription, Fee Collection, Fee Waiver Sync)
     5. `FoodWaste` (FoodWaste Bin & Preference, Contribution Logging, IoT Compost Batches)
     6. `Welfare` (Member Eligibility, Funeral/Welfare Fund Auto-Deduction, Payout Approval)
     7. `Inventory` (Stock Items, Withdrawals, Multi-Step Approval Workflow)
     8. `SmartUtility` (Stations, Devices, Pipelines, Quality Logs)
     9. `LineLiff` (LINE Token Auth, Citizen Self-Onboarding, Unified Citizen Dashboard, Issue Notify)
     10. `Kiosk` (Kiosk Session Auth, AI Bottle Classification, Hardware Command & Transaction Sync)
2. ภายในแต่ละ **Slice** (เช่น `app/Features/Tabwater/IssueMonthlyInvoices/`) จะบรรจุไฟล์ที่เกี่ยวข้องกับฟีเจอร์นั้นครบจบในที่เดียว:
   - `{Slice}Controller.php` (Single-Action Invokable Controller `__invoke`)
   - `{Slice}Request.php` (FormRequest Validation)
   - `{Slice}DTO.php` (Readonly Data Transfer Object สำหรับส่งข้อมูลเข้า Action)
   - `{Slice}Action.php` (Core Business Logic / Command-Query Handler)
   - `Views/` หรืออ้างอิง Slice View Namespace (เช่น `tabwater::issue-monthly-invoices.index`)
   - `routes.php` และ `{Slice}Test.php` (Feature Test ประจำ Slice)
3. ใช้กลไก **Strangler Fig Pattern** ในการย้ายจาก `app/Http/Controllers/*` เดิมเข้าสู่ `app/Features/*` ทีละ Slice พร้อมเขียน Feature Test กำกับทุกครั้งก่อนตัดโค้ดเก่าทิ้ง

## Consequences
- **Positive:** โค้ดที่มีเหตุผลในการเปลี่ยนแปลงเดียวกันจะอยู่ติดกัน 100% (High Cohesion), ลดขนาดไฟล์ให้เล็กและโฟกัสเฉพาะฟีเจอร์, AI Agent และนักพัฒนาสามารถทำความเข้าใจและแก้ไขแต่ละฟีเจอร์ได้โดยไม่ต้องโหลด Context ทั้งโปรเจกต์, และทดสอบแยกส่วนได้ง่าย
- **Negative:** มีจำนวนไฟล์และคลาสย่อยเพิ่มขึ้น (1 ฟีเจอร์ = 1 โฟลเดอร์ย่อย) ต้องมีระเบียบการตั้งชื่อและ ServiceProvider สำหรับโหลด Routes/Views ของแต่ละ Feature Slice
- **Constraints (สำหรับ AI Agent):**
  1. ห้ามสร้าง Fat Controller แบบเดิมที่มีหลาย Action ปะปนกันเด็ดขาด ฟีเจอร์ใหม่และฟีเจอร์ที่ Refactor แล้วจะต้องใช้ Single-Action Invokable Controller (`__invoke`) หรือแยกตาม Slice ใน `app/Features/{Domain}/{Slice}/` เสมอ
  2. ห้ามเขียน Business Logic หรือ `DB::transaction` ไว้ใน Controller ให้เขียนไว้ใน `{Slice}Action` เสมอ
  3. ห้ามให้ Slice หนึ่งในโดเมนหนึ่งแก้ไขตารางของอีกโดเมนหนึ่งโดยตรง หากต้องทำงานข้ามโดเมน ให้เรียกผ่าน Shared Action/Contract ใน `app/Shared/` หรือ Dispatch Domain Event เสมอ

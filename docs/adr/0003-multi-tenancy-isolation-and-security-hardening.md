# ADR 0003: การบังคับใช้ระบบแยกข้อมูลหลายองค์กร (Multi-Tenancy) และการปิดช่องโหว่ความปลอดภัยวิกฤต

## Status
Accepted (วันที่ 2026-11-05)

## Context
จากการตรวจสอบความปลอดภัยเชิงลึกพบช่องโหว่ระดับวิกฤต (Critical Vulnerabilities) ที่ทำให้ข้อมูลขององค์กรปกครองส่วนท้องถิ่น (อปท.) รั่วไหลข้ามกันและเสี่ยงต่อการถูกยึดบัญชี:
1. **Authentication Bypass & Account Takeover (CVSS 9.8):** ใน `LineLiffController::dashboard` มีการสั่ง `Auth::login($user)` โดยตรงจากพารามิเตอร์ URL `/line/dashboard/{pref_id}/{org_id}` โดยไม่มีการตรวจสอบตัวตนใดๆ
2. **Insecure Account Linking:** ใน `Api\LineController::verifyUser` และ `LineLiffController::update_user_by_phone` อนุญาตให้ผูกบัญชี LINE เข้ากับ `users` ด้วยการส่งเพียงเบอร์โทรศัพท์หรือเลขบัตรประชาชนโดยไม่มีการยืนยัน LINE ID Token หรือ OTP
3. **Cross-Tenant Data Leakage & IDOR:** คอนโทรลเลอร์หลายจุด (`UserWasteController::index`, `AdminMemberWasteController::index`, `RecycleBankController::history`, `WelfareMemberController::destroy`) คิวรีและลบข้อมูลโดยไม่กรอง `org_id_fk` และโมเดลส่วนใหญ่ไม่ได้ใช้ Trait `BelongsToOrganization`
4. **Unauthenticated Public APIs:** ใน `routes/api.php` เปิดเอ็นด์พอยต์ค้นหาผู้ใช้ ลบโซน และสร้างใบแจ้งหนี้เป็นสาธารณะโดยไม่มี Middleware ป้องกัน

## Decision
1. **ยกเลิกการสั่ง `Auth::login()` จาก URL Parameter โดยเด็ดขาด:**
   - สร้าง Slice `VerifyLineLiffSession` ที่บังคับรับ `idToken` จาก LINE LIFF SDK ฝั่ง Frontend แล้วตรวจสอบลายเซ็นดิจิทัลกับ LINE OAuth2 API (`POST https://api.line.me/oauth2/v2.1/verify`) ก่อนอนุญาตให้เข้าถึงข้อมูลหรือผูกบัญชี
2. **ยกระดับ `BelongsToOrganization` Trait และ `SetTenantConnection` Middleware:**
   - บังคับให้ทุก Eloquent Model ที่มีคอลัมน์ `org_id_fk` ต้องใส่ `use BelongsToOrganization;` เสมอ เพื่อให้มี Global Scope กรอง `org_id_fk` อัตโนมัติทุกคิวรี
   - สร้างคลาส `TenantContext` ใน `app/Shared/Tenancy/` เพื่อเป็น Single Source of Truth ในการดึง `currentOrgId()` แทนการเขียน `session('active_org_id') ?? Auth::user()->org_id_fk` ซ้ำซ้อน
3. **ล็อกสิทธิ์ API Routes ทั้งหมดใน `routes/api.php`:**
   - แยกกลุ่ม API ออกเป็น 3 ประเภทที่มี Middleware ชัดเจน: `auth:sanctum` (สำหรับ Staff/Mobile App), `line.verified` (สำหรับ LINE LIFF ที่มี Header `Authorization: Bearer <LINE_ID_TOKEN>`), และ `kiosk.signed` (สำหรับตู้ Kiosk ที่มี API Key / HMAC Signature ของแต่ละตู้)

## Consequences
- **Positive:** ปิดช่องโหว่การยึดบัญชี (CVSS 9.8) ทันที, ป้องกันแอดมิน อปท. หนึ่งเห็นหรือลบข้อมูลของอีก อปท. หนึ่งได้ในระดับ Eloquent Global Scope, และยกระดับความปลอดภัยของ API ทั้งระบบ
- **Negative:** ในกรณีที่ Super Admin ต้องการดูรายงานภาพรวมข้ามทุก อปท. จะต้องเรียก `Model::withoutGlobalScope('org')` อย่างจงใจใน Slice ของ Super Admin เท่านั้น
- **Constraints (สำหรับ AI Agent):**
  1. ห้ามเขียน `Auth::login($user)` หรือ `Auth::loginUsingId($id)` โดยรับค่า ID จาก Request URL / Body โดยไม่ผ่านการ Verify Cryptographic Token เด็ดขาด
  2. ทุก Model ที่มีคอลัมน์ `org_id_fk` ต้อง `use App\Shared\Tenancy\BelongsToOrganization;` เสมอ
  3. ห้ามสร้าง Route ใน `routes/api.php` หรือ Feature Slice API โดยไม่ระบุ Authentication Middleware (`auth:sanctum`, `line.verified`, หรือ `kiosk.signed`) ยกเว้น Webhook ที่มีการตรวจ Signature เท่านั้น

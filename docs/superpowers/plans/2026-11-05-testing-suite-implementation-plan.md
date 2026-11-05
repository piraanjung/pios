# แผนปฏิบัติการสร้างชุดทดสอบครบวงจร (Testing Suite Implementation Plan)

> **สำหรับ AI Agent / นักพัฒนา:** ใช้แผนปฏิบัติการนี้ควบคู่กับ [Comprehensive Testing Master Plan](../../testing/comprehensive-testing-master-plan.md) และ [ADR-0007](../../adr/0007-comprehensive-testing-strategy-and-qa-pyramid.md) เพื่อดำเนินการสร้างชุดทดสอบทั้ง 5 เลเยอร์อย่างเป็นระบบ

**Goal:** สร้างชุดทดสอบอัตโนมัติครอบคลุมทั้ง 5 เลเยอร์ ได้แก่ Unit Test, Integration Test, API Testing, UI Verification และ End-to-End (E2E) Testing สำหรับระบบ `qa_envsogo_pios`

**Architecture:** แบ่งชุดทดสอบตามโครงสร้าง Vertical Slice Architecture (VSA) ใช้ SQLite In-Memory (`:memory:`) ร่วมกับ Trait `BuildsTestSchema` สำหรับการทดสอบฝั่ง Backend/Blade ที่รวดเร็ว และใช้ Playwright สำหรับการทดสอบ E2E และ Visual UI Verification

**Tech Stack:** PHP 8.5 (`/opt/homebrew/Cellar/php/8.5.10/bin/php`), PHPUnit, Laravel Testing Helpers, SQLite In-Memory, Playwright (TypeScript)

---

## Task 1: สร้าง Test Support Infrastructure (`BuildsTestSchema` & `InteractsWithTenancy`)

**Files:**
- Create: `tests/Support/Concerns/BuildsTestSchema.php`
- Create: `tests/Support/Concerns/InteractsWithTenancy.php`
- Modify: `phpunit.xml` (เพิ่ม Test Suites แยกตามเลเยอร์: `Unit`, `Integration`, `Api`, `Ui`)

### Step 1.1: สร้าง Trait `BuildsTestSchema`
เตรียม Schema ขั้นต่ำสำหรับ SQLite In-Memory (`users`, `organizations`, `kp_user_waste_preferences`, `kp_bank_accounts`, `kp_purchase_transactions`, `kp_purchase_transaction_details`, `food_waste_user_preferences`, `welfare_fund_ledgers`) เพื่อให้ทุก Integration/API Test เรียกใช้ซ้ำได้โดยไม่ต้องเขียน `Schema::create` ซ้ำในทุกไฟล์

### Step 1.2: สร้าง Trait `InteractsWithTenancy`
เตรียม Helper สำหรับสลับ Context องค์กร เช่น `$this->actingAsOrg(int $orgId)` และ `$this->createCitizenForOrg(int $orgId, array $overrides = [])`

### Step 1.3: อัปเดต `phpunit.xml`
แบ่ง `<testsuites>` ออกเป็น:
- `Unit` (`tests/Unit`)
- `Integration` (`tests/Integration`)
- `Api` (`tests/Feature/Api`)
- `Ui` (`tests/Feature/Ui`)
- `Feature` (`tests/Feature`)

---

## Task 2: สร้างชุดทดสอบ Layer 1 — Unit Tests (`tests/Unit/`)

**Files:**
- Verify/Expand: `tests/Unit/Features/IdentityAndOnboarding/OnboardCitizenDTOTest.php`
- Create: `tests/Unit/Shared/TenantContextUnitTest.php`
- Create: `tests/Unit/Features/Waterworks/TieredWaterPricingCalculatorTest.php`
- Create: `tests/Unit/Features/RecycleBank/WastePointAndPriceCalculatorTest.php`
- Create: `tests/Unit/Features/Welfare/MembershipDurationRuleTest.php`

### Step 2.1: ทดสอบ DTOs และ Calculators
- ทดสอบ Boundary Cases ของการคิดค่าน้ำประปา (0 หน่วย, ขั้นบันได Tier 1-3, ค่ารักษามิเตอร์, VAT 7%)
- ทดสอบการคำนวณแต้มและยอดเงินธนาคารขยะจากน้ำหนักและจำนวนชิ้น
- ทดสอบกฎการนับวันเป็นสมาชิกกองทุนสวัสดิการ (179 วัน = ไม่ผ่าน, 180 วัน = ผ่าน)

### Step 2.2: รันตรวจสอบ Layer 1
```bash
/opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit tests/Unit
```

---

## Task 3: สร้างชุดทดสอบ Layer 2 — Integration Tests (`tests/Integration/`)

**Files:**
- Create: `tests/Integration/Tenancy/BelongsToOrganizationScopeTest.php`
- Create: `tests/Integration/Features/IdentityAndOnboarding/OnboardCitizenActionTest.php`
- Create: `tests/Integration/Features/Kiosk/SubmitKioskDepositActionTest.php`
- Create: `tests/Integration/Features/Welfare/CheckMemberEligibilityActionTest.php`
- Create: `tests/Integration/Features/LineLiff/VerifyLineSessionActionTest.php`

### Step 3.1: ทดสอบการแยกข้อมูลข้ามองค์กร (`BelongsToOrganizationScopeTest`)
- สร้างข้อมูลใน `Org 1` และ `Org 2`
- ยืนยันว่าเมื่อ `TenantContext::setOrgId(1)` จะคิวรีพบเฉพาะข้อมูลของ `Org 1` เท่านั้น และเมื่อสร้างเรคคอร์ดใหม่จะได้ `org_id_fk = 1` อัตโนมัติ

### Step 3.2: ทดสอบ VSA Actions และการทำธุรกรรม (`DB::transaction`)
- ทดสอบ `OnboardCitizenAction`: ตรวจสอบว่าสร้าง `User`, `KpUserWastePreference`, `KPBankAccount` (`ACC-xxxx`, `MBR-xxxx`), และ `FoodWasteUserPreference` ครบถ้วน
- ทดสอบ `SubmitKioskDepositAction`: ตรวจสอบว่าสร้าง `KpPurchaseTransaction`, รายการย่อย และบวกยอด `balance` + `points` ใน `kp_bank_accounts` ถูกต้องแม่นยำ
- ทดสอบ `CheckMemberEligibilityAction`: ทดสอบสมาชิกที่อายุสมาชิกครบ 180 วัน เทียบกับสมาชิกใหม่

### Step 3.3: รันตรวจสอบ Layer 2
```bash
/opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit tests/Integration
```

---

## Task 4: สร้างชุดทดสอบ Layer 3 — API & Contract Tests (`tests/Feature/Api/`)

**Files:**
- Create: `tests/Feature/Api/CitizenOnboardingApiTest.php`
- Create: `tests/Feature/Api/KioskTransactionApiTest.php`
- Create: `tests/Feature/Api/LineLiffSecurityApiTest.php`

### Step 4.1: ทดสอบ Contract & Validation ของ REST Endpoints
- ทดสอบ `POST /features/onboard-citizen` ทั้งกรณีสำเร็จ (`201 Created`) และกรณีข้อมูลไม่ครบ (`422 Unprocessable Entity`)
- ทดสอบ `POST /api/kiosk/submit-transaction` ทั้งกรณีส่ง JSON items ถูกต้อง, กรณีไม่ส่ง `userId` (`422`), กรณีตะกร้าว่าง (`400`), และกรณีไม่พบผู้ใช้ (`404`)
- ทดสอบ `GET /line/dashboard/{pref_id}/{org_id}` ป้องกัน IDOR ข้ามองค์กรและการล็อกอินเข้าบัญชี Admin (`403`/`404`)

### Step 4.2: รันตรวจสอบ Layer 3
```bash
/opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit tests/Feature/Api
```

---

## Task 5: สร้างชุดทดสอบ Layer 4 — UI Verification Tests (`tests/Feature/Ui/`)

**Files:**
- Create: `tests/Feature/Ui/SoftUiDesignSystemComponentTest.php`
- Create: `tests/Feature/Ui/MasterLayoutsVerificationTest.php`

### Step 5.1: ทดสอบการเรนเดอร์ `<x-soft-ui.*>` ครบทั้ง 7 Components
- ทดสอบ `<x-soft-ui.card>`, `<x-soft-ui.stat-card>`, `<x-soft-ui.button>`, `<x-soft-ui.badge>`, `<x-soft-ui.alert>`, `<x-soft-ui.modal>`, และ `<x-soft-ui.datatable>`
- ตรวจสอบ DOM classes (`bg-gradient-*`, `shadow-blur`, `badge-sm`, `modal-dialog-centered`, `table-responsive`)

### Step 5.2: ทดสอบ Master Layouts และ Flash Messages
- ทดสอบการเรนเดอร์ `layouts.soft-ui-pro` และ `layouts.partials.flash` พร้อมข้อความแจ้งเตือนภาษาไทย

### Step 5.3: รันตรวจสอบ Layer 4
```bash
/opt/homebrew/Cellar/php/8.5.10/bin/php vendor/bin/phpunit tests/Feature/Ui
```

---

## Task 6: สร้างโครงสร้าง Layer 5 — E2E & Visual Verification ด้วย Playwright (`tests/e2e/`)

**Files:**
- Create: `playwright.config.ts`
- Create: `tests/e2e/specs/01-citizen-onboarding.spec.ts`
- Create: `tests/e2e/specs/02-tabwater-billing-flow.spec.ts`
- Create: `tests/e2e/specs/03-kiosk-recycle-deposit.spec.ts`
- Create: `tests/e2e/specs/04-line-liff-member-portal.spec.ts`
- Create: `tests/e2e/specs/05-soft-ui-visual-verify.spec.ts`

### Step 6.1: ตั้งค่า Playwright Multi-Viewport Projects
- กำหนดโปรเจกต์ `desktop-chromium` (`1440x900`), `mobile-liff` (`390x844`), และ `kiosk-touch` (`1080x1920`) ใน `playwright.config.ts`
- เขียนสเปกทดสอบ 5 Critical User Journeys และ Visual Regression Snapshot

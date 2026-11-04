# รายงานการตรวจสอบเชิงลึก: ระบบจัดการผู้ใช้งานและการลงทะเบียนสมาชิกข้ามโมดูล (Core User, Tabwater, LINE LIFF, Kiosk)
**Explorer 1: Core User & Access Explorer**  
**วันที่ตรวจสอบ:** 2026-10-25  
**เป้าหมาย:** สถาปัตยกรรมข้อมูล, คอนโทรลเลอร์, โมเดล, เส้นทาง (Routes), สคีมา (Schemas), ความปลอดภัย (Security/Multi-Org) และข้อผิดพลาด PHP 8.2+

---

## สรุปภาพรวมและประเด็นวิกฤต (Executive Summary)

จากการตรวจสอบซอร์สโค้ดของแอปพลิเคชัน `qa_envsogo_pios` (Laravel / PHP 8.2+) ในส่วนที่เกี่ยวข้องกับการจัดการผู้ใช้งาน (`User`), การเปิดสิทธิ์สมาชิก (`Membership Onboarding`), น้ำประปา (`Tabwater`), ประชาชนผ่าน LINE (`LINE LIFF`), และตู้คีออสขยะ (`Kiosk`) พบปัญหาเชิงโครงสร้างและบั๊กวิกฤตที่ทำให้ระบบเกิดความสับสน ข้อมูลแตกกระจาย (Data Fragmentation) และบางฟังก์ชันทำงานล้มเหลว (Fatal Crash) ดังนี้:

1. **บั๊กวิกฤตระดับ PHP 8.2 Fatal Errors:**
   - ใน `Admin\UserController::attachMeter` (บรรทัด 548) มีการใช้ Variable-variable ผิดพลาดอย่างร้ายแรง: `'meter_id' => $$user->id` ทำให้การผูกมิเตอร์จากหน้าแก้ไขผู้ใช้ล้มเหลวทันที
   - ใน `LineLiffController::user_line_register` (บรรทัด 284) มีการเรียก `$user->org->id` ซึ่งในคลาส `User` ไม่มี Relation หรือ Property ชื่อ `org` (มีเพียง `organization`) ส่งผลให้เกิด Exception: `Attempt to read property "id" on null` และ Rollback ทุกครั้งที่ประชาชนลงทะเบียนผ่าน LINE LIFF
   - ใน `UserWasteStatusService::updateOverallUserWasteStatus` (บรรทัด 25) และ `LineLiffController::update_user_by_phone` (บรรทัด 196) มีการเรียก `$user->wastePreference()` ซึ่งไม่มี Relationship นี้ในโมเดล `User` (มีเฉพาะใน `SuperUser` และ `KioskMatch`) ทำให้ระบบเกิด `BadMethodCallException`
   - ใน `KeptKaya\UserWasteController::store` (บรรทัด 258) การสร้าง User รายบุคคลเรียก `$user->wastePreference()->create(...)` ส่งผลให้ล้มเหลว 100%

2. **ช่องโหว่ความปลอดภัยระดับร้ายแรง (Critical Security & Multi-Org Vulnerabilities):**
   - **IDOR / Instant Account Takeover:** ใน `LineLiffController::dashboard` (บรรทัด 40-49) เพียงแค่ผู้ใช้หรือบุคคลภายนอกเข้า URL `/line/dashboard/{pref_id}/{org_id}` โดยระบุ `pref_id` ระบบจะสั่ง `Auth::login($user)` ให้ทันทีโดยไม่มีการตรวจสอบ LINE Access Token, OTP หรือลายเซ็นใดๆ
   - **LINE Account Hijacking:** ใน `Api\LineController::verifyUser` (บรรทัด 101-146) และ `LineLiffController::update_user_by_phone` (บรรทัด 194-205) สามารถผูก LINE เข้ากับ User ใดๆ เพียงแค่กรอกเบอร์โทรหรือเลขบัตรประชาชน โดยไม่มี OTP / SMS ยืนยันตัวตน
   - **Multi-Org Isolation Leak:** ใน `Admin\UserController::index` (บรรทัด 53-56) กรองผู้ใช้ด้วย `whereHas('kpUserPreference', fn($q) => $q->where('org_id_fk', ...))` ทำให้ผู้ใช้ที่ลงทะเบียนผ่านประปา (Tabwater) หรือช่องทางอื่นที่ยังไม่มี `kpUserPreference` จะ "ล่องหน" จากหน้าจัดการผู้ใช้ของแอดมินทันที

3. **ความซ้ำซ้อนและการแตกกระจายของโครงสร้างบัญชีธนาคารขยะ (Triple Account Fragmentation):**
   - พบการสร้างและอ้างอิงตารางบัญชีเงิน/แต้มขยะรีไซเคิลถึง 3 ตารางที่ขัดแย้งกันอย่างสิ้นเชิง:
     1. `kp_accounts` (PK: `u_wpref_id_fk`) ผ่านโมเดล `KPAccounts`
     2. `kp_bank_accounts` (PK: `id`, คอลัมน์: `user_pref_id`, `org_id_fk`) ผ่านโมเดล `KPBankAccount`
     3. `recycle_bank_accounts` (PK: `id`, คอลัมน์: `user_id`, `org_id_fk`) ผ่านโมเดล `RecycleBankAccount`
   - แต่ละโมดูลเขียนลงคนละตาราง ทำให้ยอดเงินและคะแนนไม่เชื่อมโยงกัน

4. **Kiosk Data Hardcoding & Broken Flow:**
   - `Api\KioskController::submitTransaction` (บรรทัด 151) มีการฮาร์ดโค้ด `$userId = 10;` ทุกรายการที่ส่งจากตู้จะเข้าบัญชี User ID 10 ทั้งหมด
   - `Kiosk\KioskController::storeTransaction` (บรรทัด 261) มีการฮาร์ดโค้ด `'kp_user_w_pref_id_fk' => 13`
   - ระบบไม่มีฟอร์มลงทะเบียนสมาชิกใหม่ที่หน้าตู้ Kiosk หากสแกน/กรอกเบอร์แล้วไม่พบบัญชี จะถูกตัดทิ้ง (Error 404) ทันที และไม่มีระบบอ่านบัตรประชาชน Smart Card จริงในโค้ด (มีเพียง Input เบอร์โทรและ QR Scanner ผ่านกล้องเว็บแคม)

---

## 1. การตรวจสอบระบบ Admin User Management

### 1.1 โครงสร้างไฟล์และคอนโทรลเลอร์หลัก
- **Controller:** `app/Http/Controllers/Admin/UserController.php` (828 บรรทัด)
- **Service:** `app/Services/UserService.php` (153 บรรทัด)
- **Service:** `app/Services/UserWasteStatusService.php` (78 บรรทัด)
- **Model:** `app/Models/User.php` (317 บรรทัด)
- **Model:** `app/Models/Admin/UserProfile.php` (26 บรรทัด)
- **Excel Import:** `app/Imports/UserImport.php` (86 บรรทัด)
- **Excel Export:** `app/Exports/UserTemplateExport.php`
- **Alternative Controllers:**
  - `app/Http/Controllers/KeptKaya/UserWasteController.php` (ใช้ Route: `keptkayas/users`)
  - `app/Http/Controllers/Admin/SuperUserController.php` (ใช้ Route: `admin/super_users`)
  - `app/Http/Controllers/Auth/RegisteredUserController.php` (ใช้ Route: `register`)

### 1.2 การสร้างผู้ใช้ใหม่และการเปิดสิทธิ์บริการ (`UserController::store`)
- **ไฟล์:** `app/Http/Controllers/Admin/UserController.php` (บรรทัด 325–407)
- **กระบวนการทำงาน (Workflow):**
  1. ตรวจสอบข้อมูล Validation: `username` (unique), `password` (min:6), `firstname`, `lastname`, `zone_id`, `subzone_id`, `id_card` (unique:users,id_card)
  2. เปิด Transaction: `DB::beginTransaction()`
  3. บันทึกข้อมูลลงตาราง `users` กำหนดสถานะ `status = 'active'`, `org_id_fk = Auth::user()->org_id_fk`
  4. กำหนด Role เริ่มต้น: `$user->assignRole('User')`
  5. ดึงค่าสวิตช์บริการจาก Request: `svc_recycle`, `svc_food_waste`, `svc_annual_trash`
  6. เรียก `UserService::syncUserServices($user, $serviceData)` เพื่อสร้างบัญชีและสถานะบริการของแต่ละโมดูล
  7. สร้างเรคอร์ดใน `KpUserWastePreference::firstOrCreate(['user_id' => $user->id], ...)`
- **จุดบกพร่องที่พบ:**
  - **ไม่มีการสร้างหรือผูกมิเตอร์น้ำประปา:** ในหน้า `create` ผู้ดูแลระบบสามารถเปิดได้เฉพาะขยะรีไซเคิล, ขยะเศษอาหาร และขยะรายปี แต่น้ำประปา (`Tabwater`) ไม่สามารถเปิดจากขั้นตอนนี้ได้ ต้องสร้างผู้ใช้ให้เสร็จก่อน แล้วตามไปกดแก้ไข (`edit`) เพื่อผูกมิเตอร์ (`attachMeter`) ในภายหลัง หรือต้องสลับไปที่โมดูลน้ำประปา `tabwater/members/add`
  - **การจัดการ Transaction แบบไม่ครอบคลุม:** ใน `UserService::syncUserServices` มีการลบและสร้างข้อมูลย่อย แต่หากเกิด Error ในโมเดลย่อยบางตัว มีความเสี่ยงที่การ Rollback จะไม่สมบูรณ์

### 1.3 การผูกมิเตอร์น้ำประปาผ่านหน้าแก้ไขผู้ใช้ (`UserController::attachMeter`)
- **ไฟล์:** `app/Http/Controllers/Admin/UserController.php` (บรรทัด 531–568)
- **บั๊กวิกฤต PHP 8.2 (Fatal Bug):**
  ```php
  // app/Http/Controllers/Admin/UserController.php:548
  TwMeterInfos::create([
      'meter_id'             => $$user->id,  // ❌ Syntax Error / Undefined variable
      'user_id'              => $user->id,
      'org_id_fk'            => $orgId,
      ...
  ```
  ตัวแปร `$$user->id` เป็นการประเมิน Variable of variable ซึ่งชื่อตัวแปรจะกลายเป็นค่าของ ID (เช่น `$1`) ซึ่งไม่มีอยู่จริง ใน PHP 8.2 จะเกิด Warning/Error และส่งค่า `null` หรือ Crash ทันที ทำให้บันทึกมิเตอร์ไม่สำเร็จ
- **ความไม่สอดคล้องกับ Tabwater Module:**
  - `UserController::attachMeter` บันทึกลงตาราง `tw_meter_infos` แต่ **ไม่ได้** มอบ Role `Tabwater User` ให้กับผู้ใช้ (ต่างจาก `UserMeterInfosController::bulkStore` ที่มีคำสั่ง `$user->assignRole('Tabwater User')`)

### 1.4 การลงทะเบียนแบบสาธารณะ (`UserController::register` vs `RegisteredUserController::store`)
พบว่าระบบมีเส้นทาง `/register` ซ้ำซ้อนกัน 2 แห่ง:
1. **ใน `routes/web.php` (บรรทัด 229–230):** ชี้ไปที่ `UserController::register`
   - ตรวจสอบ Validation ฟิลด์ `organization_id`
   - **บั๊กการบันทึก Org ID ล่องหน:**
     ```php
     // app/Http/Controllers/Admin/UserController.php:752
     'organization_id' => $request->organization_id,
     ```
     ในโมเดล `User` คอลัมน์จริงคือ `org_id_fk` และใน `$fillable` ไม่มีคีย์ `organization_id` ทำให้ค่า Org ID ถูกตัดทิ้งโดย Eloquent Mass Assignment Protection ผู้ใช้ที่ลงทะเบียนเข้ามาจึงมี `org_id_fk = null` เสมอ!
2. **ใน `routes/auth.php` (บรรทัด 14–17):** ชี้ไปที่ `Auth\RegisteredUserController::store`
   - **บั๊กข้อมูลจำลองตกค้าง (Hardcoded Dummy Data):**
     ```php
     // app/Http/Controllers/Auth/RegisteredUserController.php:48-56
     'id_card' => '111111111',
     'phone' => '0999999999',
     'gender' => 'm',
     'address' => 'x',
     'zone_id' => '0',
     'subzone_id' => '0',
     ...
     ```
     หากผู้ใช้ลงทะเบียนผ่าน Laravel Breeze สมาชิกทุกคนจะได้เลขบัตรประชาชน `111111111` และเบอร์โทร `0999999999` เหมือนกันทั้งหมด ซึ่งทำให้เกิดข้อมูลชนกันทันที!

### 1.5 การนำเข้าผู้ใช้ผ่าน Excel (`UserImport`)
- **ไฟล์:** `app/Imports/UserImport.php` (86 บรรทัด)
- **จุดบกพร่องที่ตรวจพบ:**
  1. **Column / Fillable Mismatch:** บรรทัด 55 ส่ง `'is_waste_bank' => 1` แต่ในโมเดล `KpUserWastePreference` คอลัมน์ที่อยู่ใน `$fillable` คือ `is_recycle_bank` ค่านี้จึงถูกทิ้ง ส่งผลให้ผู้ใช้ที่นำเข้าผ่าน Excel ไม่ได้สิทธิ์สมาชิกธนาคารขยะในระบบ KpUserWastePreference
  2. **ความขัดแย้งของโมเดลบัญชี:** บรรทัด 68 สร้างบัญชีผ่าน `RecycleBankAccount::create([...])` ลงตาราง `recycle_bank_accounts` ในขณะที่ระบบหลักและ UserService ใช้งานตาราง `kp_bank_accounts`
  3. **ฮาร์ดโค้ดรหัสบัญชี:** บรรทัด 70 ใช้เลขบัญชี `'002' . substr('000', strlen($user->id)) . $user->id` ซึ่งล็อคค่า "002" ไว้ ไม่ได้ดึงรหัสองค์กรตามจริง
  4. **ไม่มี DB Transaction & Heading Validation:** หากมีแถวใดแถวหนึ่งเกิด Duplicate Key Exception (เช่น username/email ซ้ำ) ระบบจะพังทันทีและเกิด Orphaned Records

### 1.6 โค้ดอันตรายตกค้างใน Controller (`UserController::_index`)
- **ไฟล์:** `app/Http/Controllers/Admin/UserController.php` (บรรทัด 240–243)
- มีโค้ดคำสั่ง:
  ```php
  DB::statement('SET FOREIGN_KEY_CHECKS=0;');
  KpUserWastePreference::truncate();
  KpBankAccount::truncate();
  DB::statement('SET FOREIGN_KEY_CHECKS=1;');
  ```
  แม้ฟังก์ชันนี้จะชื่อ `_index()` แต่เป็นโค้ดทดสอบอันตรายอย่างยิ่งที่หลงเหลืออยู่ใน Production Controller ซึ่งหากมีการเรียกใช้งานจะล้างข้อมูลบัญชีผู้ใช้ทั้งระบบทันที

---

## 2. การตรวจสอบโมดูลน้ำประปา (Tabwater Module)

### 2.1 โครงสร้างไฟล์และคอนโทรลเลอร์
- **Controller:** `app/Http/Controllers/Tabwater/UserMeterInfosController.php` (614 บรรทัด)
- **Models:**
  - `app/Models/Tabwater/TwMeterInfos.php` (ตาราง `tw_meter_infos`, มี Trait `BelongsToOrganization`)
  - `app/Models/Tabwater/TwUsersInfos.php` (ตาราง `tw_meter_infos`, ไม่มี Trait)
  - `app/Models/Tabwater/TwUsersInfo.php` (ตาราง `tw_users_infos`)
  - `app/Models/Tabwater/TwMeterType.php`
  - `app/Models/Tabwater/TwInvoice.php`
- **Routes:**
  - `routes/web.php` บรรทัด 496–510 (Prefix: `tabwater/members`)
  - `routes/api.php` บรรทัด 188–189 (API: `api/members`, `api/meter-records`)

### 2.2 การลงทะเบียนสมาชิกระบบน้ำประปา (`UserMeterInfosController`)
- **กระบวนการลงทะเบียนสมาชิก (`create` & `bulkStore`):**
  - หน้า `tabwater/members/add` (`create` บรรทัด 388–414) **ไม่สามารถสร้าง User ใหม่ได้**
  - ระบบจะดึงรายชื่อ Master User ที่อยู่ในองค์กร (`User::where('org_id_fk', $orgId)`) มาแสดงเป็นตารางพร้อม Checkbox
  - ฟังก์ชัน `bulkStore` (บรรทัด 417–470) จะรับ `user_ids` ที่ถูกเลือก:
    1. วิ่งหา `max('meternumber')` ของ อปท. นั้น แล้วบวกเพิ่มทีละ 1
    2. ทำการ `create` ข้อมูลลงใน `TwMeterInfos`
    3. กำหนด Role: `$user->assignRole('Tabwater User')`
- **การเพิ่มมิเตอร์ตัวที่สอง/เพิ่มเติม (`addMeter` & `storeAdditionalMeter`):**
  - ฟังก์ชัน `addMeter` (บรรทัด 506–515) และ `storeAdditionalMeter` (บรรทัด 517–562) รองรับการที่ User 1 คนสามารถมีมิเตอร์น้ำได้มากกว่า 1 ตัว

### 2.3 ปัญหาความซ้ำซ้อนของ Model และ Table ในโมดูล Tabwater
1. **Model ชนกัน 3 ตัวสำหรับข้อมูลมิเตอร์:**
   - `TwMeterInfos` ชี้ไปที่ `tw_meter_infos` (ใช้ใน `UserMeterInfosController`, `User.php`)
   - `TwUsersInfos` ชี้ไปที่ `tw_meter_infos` (ใช้ใน `UserController::destroy`)
   - `TwUsersInfo` ชี้ไปที่ `tw_users_infos` (โมเดลดั้งเดิมที่ตกค้าง)
2. **Migrations สองตัวสร้างตารางซ้ำซ้อน:**
   - `2023_10_30_130210_create_tw_merter_infos_table.php` สร้างตาราง `tw_meter_infos`
   - `2023_10_30_130210_create_user_merter_infos_table.php` สร้างตาราง `user_meter_infos`
   (สะกดชื่อไฟล์ผิดเป็น `merter`)

### 2.4 เส้นทางที่เสียหาย (Broken Route)
- **ไฟล์:** `routes/web.php` (บรรทัด 392)
  ```php
  Route::post('/store_edited_invoice', [UserMeterInfosController::class, 'store_edited_invoice'])->name('store_edited_invoice');
  ```
  ในคลาส `UserMeterInfosController` **ไม่มี Method ชื่อ `store_edited_invoice`** อยู่จริง เมื่อมีฟอร์มยิง Request เข้ามาจะเกิด HTTP 500 (`BadMethodCallException: Method App\Http\Controllers\Tabwater\UserMeterInfosController::store_edited_invoice does not exist.`)

### 2.5 ปัญหาการลบและเพิกถอนสิทธิ์สมาชิก (Inconsistent Deletion)
- ใน `UserMeterInfosController::destroy` (บรรทัด 472–504):
  เมื่อลบมิเตอร์ประปา ระบบจะนับมิเตอร์ที่เหลือ หากไม่เหลือมิเตอร์เลย จะทำการถอด Role: `$user->removeRole('Tabwater User')` อย่างถูกต้อง
- ใน `UserController::destroy` (บรรทัด 641–701):
  การลบมิเตอร์ผ่านหน้า User Admin จะทำการอัปเดตใบแจ้งหนี้เป็น `owe` หรือ `deleted` และเปลี่ยนสถานะ User เป็น `deleted` แต่ **ไม่ได้ถอด Role 'Tabwater User' ออก**

---

## 3. การตรวจสอบระบบ LINE LIFF Onboarding

### 3.1 โครงสร้างไฟล์และคอนโทรลเลอร์ที่ซ้ำซ้อน
พบว่าระบบมี Controller จัดการ LINE 2 ตัวที่ทำงานทับซ้อนกัน:
1. `app/Http/Controllers/LineLiffController.php` (386 บรรทัด) — เรียกผ่าน `routes/web.php`
2. `app/Http/Controllers/Api/LineController.php` (703 บรรทัด) — เรียกผ่าน `routes/api.php`

### 3.2 ช่องโหว่ความปลอดภัยระดับวิกฤต (Critical Security Flaws)
1. **ช่องโหว่ IDOR Auto-Login ใน LINE LIFF Dashboard:**
   - **ไฟล์:** `app/Http/Controllers/LineLiffController.php` (บรรทัด 40–49)
   ```php
   public function dashboard(Request $request, $pref_id, $org_id)
   {
       $pref = KpUserWastePreference::findOrFail($pref_id);
       $request->session()->put('pref_id_mobile', $pref_id);

       $userId = $pref->user_id;
       $user = User::findOrFail($userId);
       Auth::login($user); // ❌ สั่ง Login ทันทีโดยไม่มีการพิสูจน์ตัวตน!
   ```
   **ผลกระทบ:** ใครก็ตามที่ทราบหรือสุ่มเลข `pref_id` สามารถเปิดเบราว์เซอร์เข้าลิงก์ `/line/dashboard/1/1` และจะได้รับการ Login เข้าสู่ระบบในฐานะ User คนนั้นทันที สามารถดูข้อมูลส่วนบุคคล แต้ม เงิน และประวัติทั้งหมดได้โดยไม่ต้องผ่าน LINE
2. **การผูกบัญชี LINE โดยไม่มี OTP (Insecure Account Binding):**
   - ใน `Api\LineController::verifyUser` (บรรทัด 101–146) และ `LineLiffController::update_user_by_phone` (บรรทัด 194–205):
     หากผู้ใช้กรอกเบอร์โทรศัพท์ ระบบจะอัปเดต `line_id` ของ User นั้นทันทีโดยไม่มีการส่ง OTP ยืนยันเจ้าของเบอร์โทร
     หากกรอกเบอร์ไม่เจอและกรอกเลขบัตรประชาชน 13 หลัก ระบบจะทำการแทนที่เบอร์โทรและผูกบัญชีทันที ทำให้ผู้ไม่หวังดีที่มีเลขบัตรประชาชนของผู้อื่นสามารถขโมยบัญชีได้โดยสมบูรณ์

### 3.3 บั๊กวิกฤต PHP 8.2 ในการลงทะเบียน LINE (`LineLiffController::user_line_register`)
- **ไฟล์:** `app/Http/Controllers/LineLiffController.php` (บรรทัด 256–358)
- **จุดที่พัง (Fatal Crash):**
  ```php
  // app/Http/Controllers/LineLiffController.php:284
  $account_no = substr('0000', strlen($user->org->id)) . $user->org->id . substr('0000', strlen($user->id)) . $user->id;
  ```
  ในโมเดล `User` **ไม่มี Relation หรือ Method ชื่อ `org`** (มีแต่ `organization()`) ใน PHP 8.2+ การเข้าถึง Property ของ `null` จะโยน Fatal Error: `Attempt to read property "id" on null`
  เนื่องจากคำสั่งนี้อยู่ในบล็อก `DB::beginTransaction()` ทำให้ฟังก์ชันเข้าสู่ `catch (\Exception $e)` และ Rollback ข้อมูลทิ้งทั้งหมด ส่งผลให้ **ประชาชนไม่สามารถลงทะเบียนผ่านฟังก์ชันนี้ได้เลย!**
- **ความไม่สมบูรณ์ของบัญชี:**
  บรรทัด 286–293 สร้าง `KpBankAccount` แต่ไม่ได้ใส่ค่า `'entity_type' => 'recycle_bank'` ส่งผลให้ Relation `$user->recycleBankAccount` ใน `User.php` (บรรทัด 267) ที่มีเงื่อนไข `where('entity_type', 'recycle_bank')` คืนค่าเป็น `null` เสมอ

### 3.4 บั๊กการเรียก Relation ที่ไม่มีอยู่จริง (`wastePreference`)
- **ไฟล์:** `app/Http/Controllers/LineLiffController.php` (บรรทัด 196)
  ```php
  $_user = User::with('wastePreference')->where('phone', $request->phoneNum)->get()->first();
  ```
  ความสัมพันธ์ `wastePreference` ไม่มีอยู่ในโมเดล `User` (ใน User มีเฉพาะ `kpUserPreference`) เมื่อเรียกใช้งานจะเกิด `RelationNotFoundException` ทันที

### 3.5 การจัดสรร ID ผู้ใช้ด้วย Sequence แบบ Manual ขัดแย้งกับ Multi-Tenant
- ใน `LineLiffController::update_user_by_phone` (บรรทัด 221–230):
  หากไม่พบ User ระบบจะไปอ่านค่าจากตาราง `SequenceNumber::where('id', 1)->first()` แล้วกำหนด `$user->id = $seqNumber->user` เองแบบ Manual ซึ่งขัดแย้งกับกลไก Auto-Increment ของ Database และกลไก Central DB ID Reservation ใน `User::booted()` (บรรทัด 89–102)

---

## 4. การตรวจสอบระบบ Kiosk Self-Registration

### 4.1 โครงสร้างไฟล์และ Controller ซ้ำซ้อน
1. `app/Http/Controllers/KioskApiController.php` (220 บรรทัด) — Controller ค้างอยู่ที่ Root namespace
2. `app/Http/Controllers/Kiosk/KioskApiController.php` (222 บรรทัด) — Controller ภายใต้ Namespace `Kiosk` ที่ Route จริงเรียกใช้
3. `app/Http/Controllers/Api/KioskController.php` (528 บรรทัด) — จัดการเซ็นเซอร์, กล้อง, และการรับส่งรายการขยะ
4. `app/Http/Controllers/Kiosk/KioskController.php` (436 บรรทัด) — จัดการคู่ตู้ (Pairing), มอนิเตอร์, และการบันทึกรายการแบบ Web

### 4.2 การอ่านบัตรประชาชน Smart Card และการ Onboarding ที่ตู้ Kiosk
- **ข้อเท็จจริงในโค้ด:** **ไม่พบโค้ดหรือการเชื่อมต่อกับเครื่องอ่านบัตรประชาชน Smart Card ใดๆ ในระบบ**
- การระบุตัวตนที่ตู้ Kiosk มีเพียง 2 วิธีตามที่ปรากฏใน Blade (`resources/views/kiosk/kiosk_login.blade.php`):
  1. การกรอกเบอร์โทรศัพท์ 10 หลัก
  2. การสแกน QR Code (รูปแบบ `user_id-08xxxxxxxx` หรือ `USER-123`) ผ่านกล้องเว็บแคมโดยใช้ไลบรารี Instascan
- **การขาดระบบลงทะเบียนสมาชิกใหม่ที่ตู้ (Missing Self-Registration):**
  ใน `KioskApiController::checkMember` (บรรทัด 73–86):
  ```php
  $user = User::where('phone', $request->phone)->first();
  if ($user) {
      return response()->json(['status' => 'found', ...]);
  }
  return response()->json(['status' => 'not_found'], 404);
  ```
  หากประชาชนยังไม่เคยลงทะเบียน ระบบจะตอบกลับ 404 และหน้าจอ Kiosk จะแสดงข้อความปฏิเสธ ไม่สามารถดำเนินการต่อหรือลงทะเบียนที่หน้าตู้ได้

### 4.3 การฮาร์ดโค้ด User ID และ Preference ID ในการทำธุรกรรมขยะ
1. **ใน `Api\KioskController::submitTransaction` (บรรทัด 151):**
   ```php
   $userId = 10; //$request->input('userId');
   ```
   มีการฮาร์ดโค้ด `$userId = 10;` ทับค่าที่ส่งมาจากเครื่อง ส่งผลให้รายการขยะทุกชิ้นที่หยอดผ่านตู้ Kiosk และส่งมายัง API นี้ จะถูกบันทึกสะสมแต้มและเงินเข้าบัญชีของ User ID 10 เพียงคนเดียวทั้งระบบ!
2. **ใน `Kiosk\KioskController::storeTransaction` (บรรทัด 261):**
   ```php
   $transaction = KpPurchaseTransaction::create([
       'org_id_fk' => $kiosk->org_id_fk,
       'kp_u_trans_no' => $transNo,
       'kp_user_w_pref_id_fk' => 13, // ❌ ฮาร์ดโค้ด ID ของ Profile ที่ผูกกับ User
       ...
   ```
   มีการฮาร์ดโค้ด `kp_user_w_pref_id_fk = 13` ทำให้ข้อมูลธุรกรรมขยะไม่ผูกกับประชาชนผู้ใช้งานจริง

### 4.4 ตรรกะผิดพลาดในการจัดเก็บรูปภาพ AI Kiosk
- **ไฟล์:** `app/Http/Controllers/Api/KioskController.php` (บรรทัด 305–314)
  ```php
  $sLabel = strtolower($request->input('sLabel', 'unknown'));
  if ($sLabel !== false) {
      $subFolder = 'kiosk_unknown_items';
  } else {
      $subFolder = 'kiosk_active_items';
  }
  ```
  เงื่อนไข `if ($sLabel !== false)` จะเป็น `true` เสมอ เพราะ `$sLabel` เป็น String ส่งผลให้รูปภาพขวดปกติทั้งหมดถูกบันทึกลงในโฟลเดอร์ขยะแปลกปลอม (`kiosk_unknown_items`) ตลอดเวลา

---

## 5. การวิเคราะห์แผนผังสคีมาฐานข้อมูลและความสัมพันธ์ (Core Schema Mapping)

### 5.1 ตารางความสัมพันธ์ของข้อมูลผู้ใช้และสมาชิกข้ามโมดูล

```
                                +-------------------+
                                |   organizations   |
                                +-------------------+
                                          | 1
                                          |
                                          | N
                                +-------------------+
                                |       users       |
                                +-------------------+
                                  | 1             | 1
        +-------------------------+               +-------------------------+
        | 1                                       | 1                       | 1
+--------------------+                  +--------------------+    +--------------------+
| user_profile       |                  | tw_meter_infos     |    | foodwaste_user_    |
| (admin user detail)|                  | (Tabwater Meter)   |    | preferences        |
+--------------------+                  +--------------------+    +--------------------+
        | 1                                       | 1                       | 1
        |                                         | N                       | 1
        | 1                               +--------------------+  +--------------------+
+--------------------+                    | tw_invoice         |  | food_waste_accounts|
| kp_user_waste_     |                    +--------------------+  +--------------------+
| preferences        |
+--------------------+
        | 1
        +-----------------------------------------+
        | 1                                       | 1
+--------------------+                  +--------------------+
| kp_bank_accounts   |                  | annual_trash_      |
| (entity_type)      |                  | subscriptions      |
+--------------------+                  +--------------------+
```

### 5.2 สรุปตารางและความสัมพันธ์หลัก
| ตารางแม่ (Parent) | ตารางลูก (Child) | Foreign Key | ความสัมพันธ์ (Relationship) | ปัญหาที่ตรวจพบ |
| :--- | :--- | :--- | :--- | :--- |
| `organizations` | `users` | `org_id_fk` | 1-to-Many | ใน `UserController::register` ส่งชื่อฟิลด์ `organization_id` ทำให้ไม่ถูกบันทึกลง DB |
| `zones` | `users` | `zone_id` | 1-to-Many | เป็น Nullable |
| `subzones` | `users` | `subzone_id` | 1-to-Many | เป็น Nullable |
| `users` | `user_profile` | `user_id` | 1-to-1 | ตารางโครงสร้างเก่า ยังหลงเหลือใน `AdminSeeder` และ `SettingsController` |
| `users` | `tw_meter_infos` | `user_id` | 1-to-Many | User 1 คนมีได้หลายมิเตอร์ แต่ Model สับสนระหว่าง `TwMeterInfos`, `TwUsersInfos` และ `TwUsersInfo` |
| `users` | `kp_user_waste_preferences`| `user_id` | 1-to-1 (Unique) | ใน Model เรียก `$fillable` คอลัมน์ `is_recycle_bank` แต่ Migration ดั้งเดิมสร้าง `is_waste_bank` |
| `users` | `kp_bank_accounts` | `user_id` | 1-to-Many | ขัดแย้งกับ `kp_accounts` (ใช้ `u_wpref_id_fk`) และ `recycle_bank_accounts` (ใช้ `user_id`) |
| `users` | `food_waste_accounts` | `user_id` | 1-to-1 | บางจุดชี้ด้วย `user_id` บางจุดชี้ด้วย `fw_pref_id_fk` |
| `users` | `annual_trash_subscriptions`| `user_id` | 1-to-Many | ฟิลด์ `billing_status` ใน UserService ไม่อยู่ใน `$fillable` |

### 5.3 การจัดการและป้องกันข้อมูลประชาชนซ้ำซ้อน (Duplicate Prevention Analysis)
จากการตรวจสอบ Schema และ Logic การป้องกันข้อมูลซ้ำซ้อน พบช่องโหว่ดังนี้:

1. **เลขบัตรประชาชน 13 หลัก (`id_card`):**
   - ในระดับ Database Schema: คอลัมน์ `id_card` ในตาราง `users` **ไม่ได้ตั้ง UNIQUE INDEX** (`$table->string('id_card')->nullable()`)
   - ในระดับ Validation: มีการเช็ค `unique:users,id_card` เฉพาะใน `UserController@store` และ `UserController@register`
   - ใน `UserImport` (Excel): **ไม่มีการตรวจสอบความซ้ำซ้อนของเลขบัตรประชาชน** ทำให้สามารถนำเข้าเลขบัตรซ้ำกันได้
   - ใน `RegisteredUserController`: มีการ Hardcode เลข `111111111`
2. **เบอร์โทรศัพท์ (`phone`):**
   - ในระดับ Database Schema: **ไม่ได้ตั้ง UNIQUE INDEX**
   - ใน `UserController@store`: เป็นเพียง `nullable|string|max:20` (ยอมให้เบอร์ซ้ำกันได้)
   - ใน `UserController@update`: เช็ค `unique:users,phone,` (ขัดแย้งกับหน้า store)
   - ใน `LineController` และ `KioskApiController`: ใช้คำสั่ง `User::where('phone', $phone)->first()` ซึ่งหากใน DB มีเบอร์ซ้ำกัน ระบบจะดึงเรคอร์ดแรกมาเสมอ ทำให้เกิดข้อผิดพลาดในการระบุตัวตน
3. **อีเมล (`email`):**
   - มีการตั้ง Unique Index ใน Database
   - แต่ในการลงทะเบียนฝั่ง Admin (`UserController@store`), LINE และ Kiosk **ไม่ได้ใช้อีเมลเป็นฟิลด์บังคับ** ทำให้ผู้ใช้ส่วนใหญ่มีค่า `email = null`
4. **LINE User ID (`line_id`, `line_user_id`):**
   - **ไม่ได้ตั้ง UNIQUE INDEX ในระดับ Database**
   - ใน `LineController::verifyUser` สามารถเอา LINE ID เดียวกันไปผูกทับ User หลายคนได้

---

## 6. ตารางสรุปข้อผิดพลาดและช่องโหว่ความปลอดภัย (Findings Matrix)

| ลำดับ | โมดูล / พื้นที่ | ไฟล์ (File Path) | Method / บรรทัด | ประเภทปัญหา | ระดับความรุนแรง | รายละเอียดข้อผิดพลาด |
|:---:|:---|:---|:---:|:---:|:---:|:---|
| 1 | Admin / User | `app/Http/Controllers/Admin/UserController.php` | `attachMeter` L.548 | Bug / PHP 8.2 | **CRITICAL** | ไวยากรณ์ผิดพลาด `$$user->id` ทำให้ผูกมิเตอร์ประปาล้มเหลวทันที |
| 2 | LINE LIFF | `app/Http/Controllers/LineLiffController.php` | `user_line_register` L.284 | Bug / PHP 8.2 | **CRITICAL** | เรียก `$user->org->id` ซึ่งไม่มี property `org` เกิด Fatal Crash ประชาชนลงทะเบียนผ่าน LINE ไม่ได้ |
| 3 | LINE LIFF | `app/Http/Controllers/LineLiffController.php` | `dashboard` L.40-49 | Security (IDOR) | **CRITICAL** | สั่ง `Auth::login($user)` โดยตรงจาก `pref_id` ใน URL โดยไม่มีการยืนยันตัวตน เกิด Account Takeover |
| 4 | LINE / Service | `app/Services/UserWasteStatusService.php` | `updateOverallUserWasteStatus` L.25 | Bug / PHP 8.2 | **HIGH** | เรียก `$user->wastePreference()` ซึ่งไม่มี Relation บน User เกิด BadMethodCallException |
| 5 | LINE LIFF | `app/Http/Controllers/LineLiffController.php` | `update_user_by_phone` L.196 | Bug / PHP 8.2 | **HIGH** | เรียก `User::with('wastePreference')` ซึ่งไม่มี Relation เกิด RelationNotFoundException |
| 6 | Kiosk | `app/Http/Controllers/Api/KioskController.php` | `submitTransaction` L.151 | Business Logic Bug | **CRITICAL** | ฮาร์ดโค้ด `$userId = 10;` ขยะทุกชิ้นที่หยอดตู้ Kiosk ถูกโอนเข้า User 10 คนเดียว |
| 7 | Kiosk | `app/Http/Controllers/Kiosk/KioskController.php` | `storeTransaction` L.261 | Business Logic Bug | **HIGH** | ฮาร์ดโค้ด `'kp_user_w_pref_id_fk' => 13` ธุรกรรมไม่ผูกกับ User จริง |
| 8 | KeptKaya / User | `app/Http/Controllers/KeptKaya/UserWasteController.php` | `store` L.258 | Bug / PHP 8.2 | **HIGH** | สร้าง User รายบุคคลเรียก `$user->wastePreference()->create(...)` พัง 100% |
| 9 | Public Auth | `app/Http/Controllers/Auth/RegisteredUserController.php` | `store` L.48-56 | Logic / Data Bug | **HIGH** | ฮาร์ดโค้ดเลขบัตรประชาชน `111111111` และเบอร์โทร `0999999999` ทุกคนที่ลงทะเบียน Breeze ได้ข้อมูลเดียวกัน |
| 10 | Admin / User | `app/Http/Controllers/Admin/UserController.php` | `register` L.752 | Mass Assignment Bug | **MEDIUM** | ส่ง `'organization_id'` แต่คอลัมน์จริงคือ `org_id_fk` ทำให้ค่า Org หลุดเป็น Null |
| 11 | Excel Import | `app/Imports/UserImport.php` | `model` L.55, 68 | Data Fragmentation | **MEDIUM** | ส่ง `is_waste_bank` แทน `is_recycle_bank` และเขียนลง `recycle_bank_accounts` ผิดตาราง |
| 12 | Tabwater | `routes/web.php` | Route L.392 | Broken Route | **MEDIUM** | ชี้ไปที่ `UserMeterInfosController::store_edited_invoice` ซึ่งไม่มี Method นี้อยู่ใน Controller |
| 13 | Admin / User | `app/Http/Controllers/Admin/UserController.php` | `index` L.53-56 | Multi-Org Isolation | **MEDIUM** | กรองด้วย `whereHas('kpUserPreference')` ทำให้ User ที่ลงทะเบียนจากโมดูลอื่น (เช่น ประปา) หายไปจากหน้ารายชื่อ |
| 14 | Admin / User | `app/Http/Controllers/Admin/UserController.php` | `_index` L.240-243 | Dangerous Code | **HIGH** | มีคำสั่ง `truncate()` ตาราง Preferences และ Bank Accounts หลงเหลืออยู่ใน Controller |
| 15 | Kiosk | `app/Http/Controllers/Api/KioskController.php` | `uploadKioskImage` L.308 | Logic Flaw | **LOW** | เงื่อนไข `if ($sLabel !== false)` เป็นจริงเสมอ รูปภาพขวดปกติถูกเก็บในโฟลเดอร์ Unknown ทั้งหมด |
| 16 | Super User | `app/Http/Controllers/Admin/SuperUserController.php` | `store` L.148 | Bug / Dead Flow | **LOW** | คำสั่ง `return $number_sequence->user = $number_sequence->user + 1;` คืนค่าตัวเลขแทนการ Redirect |

---

## 7. ข้อเสนอแนะแนวทางแก้ไขเชิงสถาปัตยกรรม (Target To-Be Recommendations)

1. **รวมศูนย์การสร้างผู้ใช้ (Unified Master User Creation Service):**
   - ยุบรวมการสร้าง User จากทุกช่องทาง (`UserController@store`, `UserWasteController@store`, `UserImport`, `LineLiffController`, `Kiosk`) ให้ผ่าน `UserService::createMasterUser()` จุดเดียว
   - กำหนดให้ตรวจสอบความซ้ำซ้อนของเบอร์โทรศัพท์และเลขบัตรประชาชนอย่างเข้มงวด
2. **แก้ไขความสัมพันธ์ที่ผิดพลาดบนโมเดล User:**
   - เพิ่ม Relationship `organization()` และสร้าง Alias `org()` ชี้ไปที่ `Organization::class` เพื่อความเข้ากันได้
   - สร้าง Alias `wastePreference()` ชี้ไปยัง `kpUserPreference()` เพื่อแก้ปัญหา Crash ใน `UserWasteStatusService` และ `UserWasteController`
3. **จัดระเบียบตารางบัญชีธนาคารขยะ (Standardize Recycle Account Table):**
   - เลือกใช้ตารางมาตรฐานเพียงตารางเดียว เช่น `kp_bank_accounts` (โดยมีคอลัมน์ `user_id`, `org_id_fk`, `entity_type`)
   - ยกเลิกการใช้งาน `recycle_bank_accounts` และ `kp_accounts` ที่ซ้ำซ้อน
4. **ปิดช่องโหว่ความปลอดภัย LINE LIFF & OTP:**
   - นำ `Auth::login($user)` ออกจากหน้า `LineLiffController::dashboard` และเปลี่ยนไปใช้ LINE LIFF ID Token Verification ส่งไปตรวจสอบกับ LINE API Server
   - กำหนดให้มีระบบยืนยันตัวตนด้วย OTP สำหรับการผูกเบอร์โทรศัพท์ครั้งแรก
5. **แก้ไข Kiosk Workflow:**
   - ปลดฮาร์ดโค้ด `$userId = 10;` และ `kp_user_w_pref_id_fk = 13` รับค่าจาก Session ของผู้ใช้งานจริง
   - ออกแบบหน้าลงทะเบียนประชาชนแบบ Quick Registration บนตู้ Kiosk หรือแสดง QR Code ให้สแกนไปลงทะเบียนผ่าน LINE LIFF ก่อนเริ่มใช้งาน
